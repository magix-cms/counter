TRUNCATE TABLE `mc_counter_content`;
DROP TABLE `mc_counter_content`;
TRUNCATE TABLE `mc_counter`;
DROP TABLE `mc_counter`;

DELETE FROM `mc_admin_access` WHERE `id_module` IN (
    SELECT `id_module` FROM `mc_module` as m WHERE m.name = 'counter'
);