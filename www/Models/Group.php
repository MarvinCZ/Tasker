<?php

namespace Models;

use Models\Base\Group as BaseGroup;
use Models\UserGroup;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * Skeleton subclass for representing a row from the 'group' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Group extends BaseGroup
{
	/**
	 * @param Models\User user
	 * @param  integer level of access (0 - read, 1 - 0 + write, 2 - 1 + manage, 3 - owner)
	 */
	public function addUserWithRights($user, $rights){
		$usergroup = new UserGroup();
		$usergroup->setUser($user);
		$usergroup->setRights($rights);
		$this->addUserGroup($usergroup);
	}

	public function canManage($user){
		return $this->getRightsForUser($user) >= 2;
	}

	public function isOwner($user){
		return $this->getRightsForUser($user) >= 3;
	}

	public function getRightsForUser($user){
		$criteria = new Criteria();
		$criteria->add('user_group.user_id', $user->getId(), Criteria::EQUAL);
		$relation = $this->getUserGroupsJoinUser($criteria)->getFirst();
		if(!$relation)
			return 0;
		return $relation->getRights();
	}

	public function getShowPath(){
		return "/settings/group/".$this->getId();
	}

}
