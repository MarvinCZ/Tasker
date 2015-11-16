
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `nick` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `rights` INTEGER,
    `email_confirmed_at` DATETIME,
    `password` VARCHAR(50) NOT NULL,
    `password_reset_token` VARCHAR(50),
    `signin_count` INTEGER,
    `email_confirm_token` VARCHAR(50),
    `avatar_path` VARCHAR(255),
    `last_signin_at` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- note
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `note`;

CREATE TABLE `note`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER NOT NULL,
    `importance` INTEGER DEFAULT -1,
    `title` VARCHAR(25),
    `deadline` DATETIME,
    `category_id` INTEGER,
    `state` TINYINT DEFAULT 0 NOT NULL,
    `repeat_after` INTEGER,
    `done_at` DATETIME,
    `public` TINYINT(1),
    `description` VARCHAR(300),
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

-- ---------------------------------------------------------------------
-- sub_note
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `sub_note`;

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

-- ---------------------------------------------------------------------
-- file
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `file`;

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

-- ---------------------------------------------------------------------
-- category
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER NOT NULL,
    `name` VARCHAR(20),
    `color` VARCHAR(6) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `category_fi_29554a` (`user_id`),
    CONSTRAINT `category_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- notification
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `notification`;

CREATE TABLE `notification`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER NOT NULL,
    `origin_id` INTEGER,
    `origin_type` VARCHAR(55),
    `type` TINYINT DEFAULT 0 NOT NULL,
    `text` VARCHAR(50),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `notification_fi_29554a` (`user_id`),
    INDEX `notification_fi_fc7bd5` (`origin_type`, `origin_id`),
    CONSTRAINT `notification_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- comment
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `comment`;

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

-- ---------------------------------------------------------------------
-- identity
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `identity`;

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

-- ---------------------------------------------------------------------
-- group
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `group`;

CREATE TABLE `group`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- user_group
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user_group`;

CREATE TABLE `user_group`
(
    `user_id` INTEGER NOT NULL,
    `group_id` INTEGER NOT NULL,
    `rights` INTEGER DEFAULT 0 NOT NULL,
    PRIMARY KEY (`user_id`,`group_id`),
    INDEX `user_group_fi_0278b4` (`group_id`),
    CONSTRAINT `user_group_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`),
    CONSTRAINT `user_group_fk_0278b4`
        FOREIGN KEY (`group_id`)
        REFERENCES `group` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- shared
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `shared`;

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
