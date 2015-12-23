<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1450885939.
 * Generated on 2015-12-23 16:52:19 by marvin
 */
class PropelMigration_1450885939
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

DROP VIEW user_note;

CREATE VIEW user_note AS
SELECT note.id as note_id, user.id as user_id,
  CASE WHEN note.user_id = user.id
               THEN 3
               ELSE MAX(shared.rights)
       END as rights
  FROM user
LEFT JOIN user_group ON (user.id=user_group.user_id)
LEFT JOIN group_of_users ON (group_of_users.id=user_group.group_id)
LEFT JOIN shared ON
  ((shared.to_id=user.id) AND (shared.to_type="user"))
  OR
  ((shared.to_id=group_of_users.id) AND (shared.to_type="group"))
LEFT JOIN category ON
  (shared.what_id=category.id)
  AND
  (shared.what_type="category")
LEFT JOIN note ON 
  (note.category_id=category.id)
  OR
  ((shared.what_id=note.id) AND (shared.what_type="note"))
  OR
  (note.user_id=user.id)
GROUP BY note_id, user_id;

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

DROP VIEW user_note;

CREATE VIEW user_note AS
SELECT note.id, user.id as userid FROM user
LEFT JOIN note ON (user.id = note.user_id)
WHERE note.id IS NOT NULL
UNION
SELECT note.id, user.id as userid FROM user
LEFT JOIN shared ON (user.id=shared.to_id) AND (shared.to_type="user")
LEFT JOIN note ON (shared.what_id=note.id) AND (shared.what_type="note")
WHERE note.id IS NOT NULL
UNION
SELECT note.id, user.id as userid FROM user
LEFT JOIN shared ON (user.id=shared.to_id) AND (shared.to_type="user")
LEFT JOIN category ON (shared.what_id=category.id) AND (shared.what_type="category")
LEFT JOIN note ON (note.category_id=category.id)
WHERE note.id IS NOT NULL;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}