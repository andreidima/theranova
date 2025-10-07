-- SQL script to add the optional `nr_data` field for CAS decisions on the `incasari` table.
ALTER TABLE `incasari`
    ADD COLUMN `nr_data` VARCHAR(255) NULL AFTER `data`;
