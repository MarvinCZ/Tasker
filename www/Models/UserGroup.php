<?php

namespace Models;

use Models\Group;
use Models\Base\UserGroup as BaseUserGroup;

/**
 * Skeleton subclass for representing a row from the 'user_group' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class UserGroup extends BaseUserGroup
{
	public function getTranslatedRights(){
		return t('rights.'.$this->getRights());
	}

	public function getPossibleTranslatedRights(){
		return Group::getTranslatedRights($this->getRights());
	}
}
