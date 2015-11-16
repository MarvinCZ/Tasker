<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1447707864.
 * Generated on 2015-11-16 22:04:24 by marvin
 */
class PropelMigration_1447707864
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

ALTER TABLE `notification`

  ADD `user_id` INTEGER NOT NULL AFTER `id`;

CREATE INDEX `notification_fi_29554a` ON `notification` (`user_id`);

ALTER TABLE `notification` ADD CONSTRAINT `notification_fk_29554a`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`);

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

ALTER TABLE `notification` DROP FOREIGN KEY `notification_fk_29554a`;

DROP INDEX `notification_fi_29554a` ON `notification`;

ALTER TABLE `notification`

  DROP `user_id`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}