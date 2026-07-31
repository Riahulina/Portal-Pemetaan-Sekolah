-- ============================================================
-- RLS setup (run as postgres, via database/sql/rls_run.php or SQL editor)
-- app_role itself is created separately (password never stored in repo).
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
