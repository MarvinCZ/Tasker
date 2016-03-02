<?php

namespace Models;

use Models\Base\Category as BaseCategory;
use Propel\Runtime\Propel;
use Propel\Runtime\Connection\ConnectionInterface;
use Models\Shared;
use \PDO;

/**
 * Skeleton subclass for representing a row from the 'category' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Category extends BaseCategory
{
	public function preSave(ConnectionInterface $con = null)
	{
		return $this->validate();
	}

	/**
	 * Return path to category's show page
	 * @return string path to show
	 */
	public function getShowPath(){
		return "/category/".$this->getId();
	}

	/**
	 * Make connection between category and group or user
	 * @param  mixed(Models\Group, Models\User) to who should it be shared
	 * @param  integer level of access (0 - read, 1 - 0 + write, 2 - 1 + manage, 3 - owner)
	 */
	public function share($to, $rights = 0){
		$s = new Shared();
		if(is_a($to, "Models\Group"))
			$s->setGroup($to);
		if(is_a($to, "Models\User"))
			$s->setUser($to);
		$this->addShared($s);
	}

	public function getSharedTo(){
		$sql =  "SELECT shared.id, shared.rights, shared.what_type, shared.to_id, shared.to_type, CASE WHEN user.id IS NULL THEN group_of_users.name ELSE user.nick END AS name, CASE WHEN user.id IS NULL THEN COUNT(group_user.id) ELSE 1 END AS user_count FROM category LEFT JOIN shared ON (category.id = shared.what_id) AND (shared.what_type = 'category') LEFT JOIN user ON (shared.to_id=user.id) AND (shared.to_type='user') LEFT JOIN group_of_users ON (shared.to_id=group_of_users.id) AND (shared.to_type='group') LEFT JOIN user_group ON (group_of_users.id=user_group.group_id) LEFT JOIN user AS group_user ON (user_group.user_id=group_user.id) WHERE category.id = ? AND shared.id IS NOT NULL GROUP BY shared.id";
		$con = Propel::getWriteConnection(Map\NoteTableMap::DATABASE_NAME);
		$stmt = $con->prepare($sql);
		$stmt->execute(array($this->getId()));
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}
