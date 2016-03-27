<?php

namespace Models;

use Models\Base\Group as BaseGroup;
use Models\UserGroup;
use Models\LinkShareGroup;
use Models\LinkShareGroupQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\ActiveQuery\Criteria;
use Helpers\ConfigHelper;

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
	public function preInsert(ConnectionInterface $con = null){
		$this->setLink(md5(time()));
		return true;
	}

	public function getJoinLink(){
		return ConfigHelper::getValue('app.url') . '/share/join/' . $this->getLink();
	}

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
		$relation = $this->getRelationWithUser($user);
		if(!$relation)
			return 0;
		return $relation->getRights();
	}

	public function getRelationWithUser($user){
		$criteria = new Criteria();
		$criteria->add('user_group.user_id', $user->getId(), Criteria::EQUAL);
		return $this->getUserGroupsJoinUser($criteria)->getFirst();
	}

	public function getShowPath(){
		return "/settings/group/".$this->getId();
	}

	public function getSharedNotes(){
		$criteria = new Criteria();
		$criteria->add('shared.what_type', 'note', Criteria::EQUAL);
		$criteria->addJoin('shared.what_id', 'note.id');
		$criteria->addJoin('note.category_id', 'category.id', Criteria::LEFT_JOIN);
		return $this->getShareds($criteria);
	}

	public function getSharedCategories(){
		$criteria = new Criteria();
		$criteria->add('shared.what_type', 'category', Criteria::EQUAL);
		return $this->getSharedsJoinCategory($criteria);
	}

	public function leave($user){
		$relation = $this->getRelationWithUser($user);
		if(!$relation){
			return;
		}
		$relation->delete();
		$criteria = new Criteria();
		$criteria->addDescendingOrderByColumn('user_group.rights');
		$relation = $this->getUserGroups($criteria)->getFirst();
		if(!$relation){
			UserGroupQuery::create()->
				filterByGroup($group)->
				delete();
			SharedQuery::create()->
				filterByGroup($group)->
				delete();
			$group->delete();
		}
		elseif($relation->getRights() != 3){
			$relation->setRights(3);
			$relation->save();
		}
	}

	public function delete(ConnectionInterface $con = null){		
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(Map\GroupTableMap::DATABASE_NAME);
        }

		UserGroupQuery::create()->
			filterByGroup($this)->
			delete();
		SharedQuery::create()->
			filterByGroup($this)->
			delete();
		LinkShareGroupQuery::create()->
			filterByGroup($this)->
			delete();

		parent::delete($con);
	}

	public static function getTranslatedRights($max = 3){
		$arr = [];
		for ($i = 0; $i <= $max; $i++) { 
			$arr[$i] = t('rights.' . $i);
		}
		return $arr;
	}
}
