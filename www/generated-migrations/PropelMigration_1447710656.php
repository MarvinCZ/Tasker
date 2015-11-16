<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1447710656.
 * Generated on 2015-11-16 22:50:56 by marvin
 */
class PropelMigration_1447710656
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

CREATE TABLE `comment`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER NOT NULL,
    `note_id` INTEGER NOT NULL,
    `text` VARCHAR(150) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `comment_fi_29554a` (`user_id`),
    INDEX `comment_fi_b85003` (`note_id`),
    CONSTRAINT `comment_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`),
    CONSTRAINT `comment_fk_b85003`
        FOREIGN KEY (`note_id`)
        REFERENCES `note` (`id`)
) ENGINE=InnoDB;

CREATE TABLE `identity`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER NOT NULL,
    `provider` VARCHAR(15) NOT NULL,
    `uid` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `identity_fi_29554a` (`user_id`),
    CONSTRAINT `identity_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
) ENGINE=InnoDB;

CREATE TABLE `group`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `user_group`
(
    `user_id` INTEGER NOT NULL,
    `group_id` INTEGER NOT NULL,
    `rights` INTEGER,
    PRIMARY KEY (`user_id`,`group_id`),
    INDEX `user_group_fi_0278b4` (`group_id`),
    CONSTRAINT `user_group_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`),
    CONSTRAINT `user_group_fk_0278b4`
        FOREIGN KEY (`group_id`)
        REFERENCES `group` (`id`)
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

DROP TABLE IF EXISTS `comment`;

DROP TABLE IF EXISTS `identity`;

DROP TABLE IF EXISTS `group`;

DROP TABLE IF EXISTS `user_group`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}