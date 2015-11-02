
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
    `created_at` DATE,
    `updated_at` DATE,
    `email_confirmed_at` DATE,
    `password` VARCHAR(50) NOT NULL,
    `password_reset_token` VARCHAR(50),
    `signin_count` INTEGER,
    `email_confirm_token` VARCHAR(50),
    `avatar_path` VARCHAR(255),
    `last_signin_at` DATE,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
