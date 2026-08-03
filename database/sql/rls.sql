-- ============================================================
-- RLS setup (run as postgres in the Supabase SQL Editor)
-- app_role itself is created separately (password never stored in repo).
--
-- DEFENSE-IN-DEPTH PREMISE: this RLS scheme relies on session variables
-- (app.user_id / app.is_admin) bridged by the SetRlsContext middleware and
-- the TransactionBeginning listener in AppServiceProvider. It contains
-- app-layer authz bugs (e.g., a controller missing a
-- ->where('user_id', auth()->id())), but it does NOT stop an attacker who
-- already holds the app_role credentials, because Postgres session
-- variables are client-settable.
--
-- OWNERSHIP / FORCE REQUIREMENT: Laravel migrations run through the
-- app_role connection, so app_role likely OWNS these tables. Postgres does
-- not apply RLS to the table owner unless FORCE ROW LEVEL SECURITY is
-- enabled — hence every table below is FORCEd. Run as postgres so
-- ENABLE / FORCE / CREATE POLICY succeed regardless of table ownership.
--
-- FRAMEWORK-TABLE PASSTHROUGH: sessions, password_reset_tokens, cache,
-- cache_locks, jobs, job_batches and migrations are written by guests,
-- queue workers and the CLI with no authenticated GUC context, so they get
-- RLS enabled + a permissive FOR ALL policy. Their real access control is
-- that no controller/API reads them and GRANTs limit them to app_role.
-- ============================================================

GRANT USAGE ON SCHEMA public TO app_role;
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO app_role;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA public TO app_role;

ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public
    GRANT ALL PRIVILEGES ON TABLES TO app_role;
ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public
    GRANT USAGE, SELECT ON SEQUENCES TO app_role;

-- ------------------------------------------------------------
-- 1. sekolah — public read, admin write
-- ------------------------------------------------------------
ALTER TABLE sekolah ENABLE ROW LEVEL SECURITY;

DROP POLICY IF EXISTS sekolah_select_public ON sekolah;
CREATE POLICY sekolah_select_public ON sekolah
    FOR SELECT USING (true);

DROP POLICY IF EXISTS sekolah_insert_admin ON sekolah;
CREATE POLICY sekolah_insert_admin ON sekolah
    FOR INSERT WITH CHECK (current_setting('app.is_admin', true) = 'true');

DROP POLICY IF EXISTS sekolah_update_admin ON sekolah;
CREATE POLICY sekolah_update_admin ON sekolah
    FOR UPDATE
    USING (current_setting('app.is_admin', true) = 'true')
    WITH CHECK (current_setting('app.is_admin', true) = 'true');

DROP POLICY IF EXISTS sekolah_delete_admin ON sekolah;
CREATE POLICY sekolah_delete_admin ON sekolah
    FOR DELETE USING (current_setting('app.is_admin', true) = 'true');

-- ------------------------------------------------------------
-- 2. sekolah_temporary — user owns rows, admin sees all
-- ------------------------------------------------------------
ALTER TABLE sekolah_temporary ENABLE ROW LEVEL SECURITY;

DROP POLICY IF EXISTS temp_select_own_or_admin ON sekolah_temporary;
CREATE POLICY temp_select_own_or_admin ON sekolah_temporary
    FOR SELECT
    USING (current_setting('app.is_admin', true) = 'true'
        OR user_id = current_setting('app.user_id', true)::bigint);

DROP POLICY IF EXISTS temp_insert_own ON sekolah_temporary;
CREATE POLICY temp_insert_own ON sekolah_temporary
    FOR INSERT
    WITH CHECK (current_setting('app.is_admin', true) = 'true'
        OR user_id = current_setting('app.user_id', true)::bigint);

DROP POLICY IF EXISTS temp_update_own_or_admin ON sekolah_temporary;
CREATE POLICY temp_update_own_or_admin ON sekolah_temporary
    FOR UPDATE
    USING (current_setting('app.is_admin', true) = 'true'
        OR user_id = current_setting('app.user_id', true)::bigint)
    WITH CHECK (current_setting('app.is_admin', true) = 'true'
        OR user_id = current_setting('app.user_id', true)::bigint);

DROP POLICY IF EXISTS temp_delete_own_or_admin ON sekolah_temporary;
CREATE POLICY temp_delete_own_or_admin ON sekolah_temporary
    FOR DELETE
    USING (current_setting('app.is_admin', true) = 'true'
        OR user_id = current_setting('app.user_id', true)::bigint);

-- ------------------------------------------------------------
-- 3. activity_logs — insert when authenticated, read only admin
-- ------------------------------------------------------------
ALTER TABLE activity_logs ENABLE ROW LEVEL SECURITY;

DROP POLICY IF EXISTS activity_insert_authenticated ON activity_logs;
CREATE POLICY activity_insert_authenticated ON activity_logs
    FOR INSERT WITH CHECK (current_setting('app.user_id', true) IS NOT NULL
        AND user_id = current_setting('app.user_id', true)::bigint);

DROP POLICY IF EXISTS activity_select_admin ON activity_logs;
CREATE POLICY activity_select_admin ON activity_logs
    FOR SELECT USING (current_setting('app.is_admin', true) = 'true');

DROP POLICY IF EXISTS activity_select_owner ON activity_logs;
CREATE POLICY activity_select_owner ON activity_logs
    FOR SELECT USING (current_setting('app.user_id', true) IS NOT NULL
        AND user_id = current_setting('app.user_id', true)::bigint);

-- ------------------------------------------------------------
-- 4. users — app-layer auth, RLS defense-in-depth
-- ------------------------------------------------------------
ALTER TABLE users ENABLE ROW LEVEL SECURITY;
ALTER TABLE users FORCE ROW LEVEL SECURITY;

-- INSERT: guests must register / sign up via Google.
DROP POLICY IF EXISTS users_insert_app ON users;
CREATE POLICY users_insert_app ON users
    FOR INSERT WITH CHECK (true);

-- SELECT: login, password reset and the unique:users rule all read users
-- by email in a guest context (app.user_id = 0); the app layer enforces
-- own-row reads.
DROP POLICY IF EXISTS users_select_app ON users;
CREATE POLICY users_select_app ON users
    FOR SELECT USING (true);

-- UPDATE: authenticated users own their row, admins all; guests
-- (app.user_id = 0) are allowed so social-login and remember-token writes
-- that occur before a session is established still work.
DROP POLICY IF EXISTS users_update_own_admin ON users;
CREATE POLICY users_update_own_admin ON users
    FOR UPDATE
    USING (current_setting('app.is_admin', true) = 'true'
        OR current_setting('app.user_id', true)::bigint = id
        OR current_setting('app.user_id', true)::bigint = 0)
    WITH CHECK (current_setting('app.is_admin', true) = 'true'
        OR current_setting('app.user_id', true)::bigint = id
        OR current_setting('app.user_id', true)::bigint = 0);

-- DELETE: admins only (AdminUserController::destroy).
DROP POLICY IF EXISTS users_delete_admin ON users;
CREATE POLICY users_delete_admin ON users
    FOR DELETE USING (current_setting('app.is_admin', true) = 'true');

-- ------------------------------------------------------------
-- 5. Framework tables — permissive passthrough
--    (sessions, password_reset_tokens, cache, cache_locks,
--     jobs, job_batches, migrations)
-- ------------------------------------------------------------
DO $$
DECLARE t text;
BEGIN
    FOREACH t IN ARRAY ARRAY['sessions','password_reset_tokens','cache','cache_locks','jobs','job_batches','migrations']
    LOOP
        EXECUTE format('ALTER TABLE %I ENABLE ROW LEVEL SECURITY', t);
        EXECUTE format('ALTER TABLE %I FORCE ROW LEVEL SECURITY', t);
        EXECUTE format('DROP POLICY IF EXISTS %I_app_all ON %I', t, t);
        EXECUTE format('CREATE POLICY %I_app_all ON %I FOR ALL USING (true) WITH CHECK (true)', t, t);
    END LOOP;
END $$;

-- ------------------------------------------------------------
-- 6. FORCE RLS on existing business tables
--    Policies already defined above (sekolah, sekolah_temporary,
--    activity_logs) and in rls_laporan_koreksis.sql (laporan_koreksis).
--    FORCE makes them bind when app_role is the table owner.
-- ------------------------------------------------------------
ALTER TABLE sekolah FORCE ROW LEVEL SECURITY;
ALTER TABLE sekolah_temporary FORCE ROW LEVEL SECURITY;
ALTER TABLE activity_logs FORCE ROW LEVEL SECURITY;
ALTER TABLE laporan_koreksis FORCE ROW LEVEL SECURITY;
