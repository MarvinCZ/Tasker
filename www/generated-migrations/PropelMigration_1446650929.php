<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1446650929.
 * Generated on 2015-11-04 16:28:49 by marvin
 */
class PropelMigration_1446650929
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

ALTER TABLE `user`

  CHANGE `email_confirmed_at` `email_confirmed_at` DATETIME,

  CHANGE `last_signin_at` `last_signin_at` DATETIME;

CREATE TABLE `note`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER NOT NULL,
    `importance` INTEGER DEFAULT -1,
    `title` VARCHAR(20),
    `deadline` DATETIME,
    `category_id` INTEGER,
    `state` TINYINT DEFAULT 0 NOT NULL,
    `repeat_after` INTEGER,
    `done_at` DATETIME,
    `public` TINYINT(1),
    `description` VARCHAR(120),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `note_fi_29554a` (`user_id`),
    INDEX `note_fi_904832` (`category_id`),
    CONSTRAINT `note_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`),
    CONSTRAINT `note_fk_904832`
        FOREIGN KEY (`category_id`)
        REFERENCES `category` (`id`)
) ENGINE=InnoDB;

CREATE TABLE `category`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER NOT NULL,
    `name` VARCHAR(20),
    `color` VARCHAR(6),
    PRIMARY KEY (`id`),
    INDEX `category_fi_29554a` (`user_id`),
    CONSTRAINT `category_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
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

DROP TABLE IF EXISTS `note`;

DROP TABLE IF EXISTS `category`;

ALTER TABLE `user`

  CHANGE `email_confirmed_at` `email_confirmed_at` DATE,

  CHANGE `last_signin_at` `last_signin_at` DATE;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}