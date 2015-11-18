<?php

namespace Models;

use Models\Base\User as BaseUser;

/**
 * Skeleton subclass for representing a row from the 'user' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class User extends BaseUser
{
	public function getAvatarPath(){
		$path = parent::getAvatarPath();
		if($path == null){
			$path = "default.png";
		}
		return "Uploads/Avatars/".$path;
	}

	public function getDisplayName(){
		return $this->getNick();
	}

	public function getPath(){
		return "users/".$this->getId();
	}

}
