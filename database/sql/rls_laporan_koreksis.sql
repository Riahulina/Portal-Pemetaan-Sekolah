-- ============================================================
-- laporan_koreksis RLS policies (amended block, user_id-based)
-- Requires the laporan_koreksis.user_id column to exist
-- (see migration add_user_id_to_laporan_koreksis_table).
-- Run as postgres AFTER that migration.
-- ============================================================

ALTER TABLE laporan_koreksis ENABLE ROW LEVEL SECURITY;

DROP POLICY IF EXISTS laporan_select_own_or_admin ON laporan_koreksis;
CREATE POLICY laporan_select_own_or_admin ON laporan_koreksis
    FOR SELECT
    USING (current_setting('app.is_admin', true) = 'true'
        OR user_id = current_setting('app.user_id', true)::bigint);

DROP POLICY IF EXISTS laporan_insert_own ON laporan_koreksis;
CREATE POLICY laporan_insert_own ON laporan_koreksis
    FOR INSERT
    WITH CHECK (current_setting('app.user_id', true) IS NOT NULL
        AND user_id = current_setting('app.user_id', true)::bigint);

DROP POLICY IF EXISTS laporan_update_admin ON laporan_koreksis;
CREATE POLICY laporan_update_admin ON laporan_koreksis
    FOR UPDATE
    USING (current_setting('app.is_admin', true) = 'true')
    WITH CHECK (current_setting('app.is_admin', true) = 'true');

DROP POLICY IF EXISTS laporan_delete_admin ON laporan_koreksis;
CREATE POLICY laporan_delete_admin ON laporan_koreksis
    FOR DELETE USING (current_setting('app.is_admin', true) = 'true');
