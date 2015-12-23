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
	/**
	 * @return string path to user's avatar
	 */
	public function getAvatarPath(){
		$path = parent::getAvatarPath();
		if($path == null){
			$path = "default.png";
		}
		return "Uploads/Avatars/".$path;
	}

	/**
	 * @return string user's display name
	 */
	public function getDisplayName(){
		return $this->getNick();
	}

	/**
	 * @return string path to user's profile
	 */
	public function getPath(){
		return "users/".$this->getId();
	}

	/**
	 * Saves hashed password
	 * @param string new password
	 */
	public function setPassword($v){
		parent::setPassword(sha1($v));
	}

	/**
	 * @param  string password
	 * @return boolean is password correct
	 */
	public function checkPassword($password){
		return $this->getPassword() == sha1($password);
	}
}
