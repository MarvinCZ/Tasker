<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1450803665.
 * Generated on 2015-12-22 18:01:05 by marvin
 */
class PropelMigration_1450803665
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

RENAME TABLE `group` TO `group_of_users`;

ALTER TABLE `user_group` DROP FOREIGN KEY `user_group_fk_0278b4`;

DROP INDEX `user_group_fi_0278b4` ON `user_group`;

CREATE INDEX `user_group_fi_3a4cbf` ON `user_group` (`group_id`);

ALTER TABLE `user_group` ADD CONSTRAINT `user_group_fk_3a4cbf`
    FOREIGN KEY (`group_id`)
    REFERENCES `group_of_users` (`id`);

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

RENAME TABLE `group_of_users` TO `group`;

ALTER TABLE `user_group` DROP FOREIGN KEY `user_group_fk_3a4cbf`;

DROP INDEX `user_group_fi_3a4cbf` ON `user_group`;

CREATE INDEX `user_group_fi_0278b4` ON `user_group` (`group_id`);

ALTER TABLE `user_group` ADD CONSTRAINT `user_group_fk_0278b4`
    FOREIGN KEY (`group_id`)
    REFERENCES `group` (`id`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}