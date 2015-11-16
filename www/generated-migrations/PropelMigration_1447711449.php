<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1447711449.
 * Generated on 2015-11-16 23:04:09 by marvin
 */
class PropelMigration_1447711449
{
    public $comment = '';

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
        // add the post-migration code here
    }

    public function preDown($manager)
    {
        // add the pre-migration code here
    }

    public function postDown($manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `note`

  CHANGE `description` `description` VARCHAR(300);

ALTER TABLE `user_group`

  CHANGE `rights` `rights` INTEGER DEFAULT 0 NOT NULL;

CREATE TABLE `sub_note`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `note_id` INTEGER NOT NULL,
    `text` VARCHAR(100),
    `state` TINYINT DEFAULT 0 NOT NULL,
    `done_at` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `sub_note_fi_b85003` (`note_id`),
    CONSTRAINT `sub_note_fk_b85003`
        FOREIGN KEY (`note_id`)
        REFERENCES `note` (`id`)
) ENGINE=InnoDB;

CREATE TABLE `file`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `note_id` INTEGER NOT NULL,
    `path` VARCHAR(150),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `file_fi_b85003` (`note_id`),
    CONSTRAINT `file_fk_b85003`
        FOREIGN KEY (`note_id`)
        REFERENCES `note` (`id`)
) ENGINE=InnoDB;

CREATE TABLE `shared`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `what_id` INTEGER,
    `what_type` VARCHAR(55),
    `to_id` INTEGER,
    `to_type` VARCHAR(55),
    `rights` INTEGER DEFAULT 0 NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `shared_fi_8413c1` (`what_type`, `what_id`),
    INDEX `shared_fi_8b8af1` (`to_type`, `to_id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `sub_note`;

DROP TABLE IF EXISTS `file`;

DROP TABLE IF EXISTS `shared`;

ALTER TABLE `note`

  CHANGE `description` `description` VARCHAR(120);

ALTER TABLE `user_group`

  CHANGE `rights` `rights` INTEGER;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}