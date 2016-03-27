<?php

namespace Models;

use Models\Base\User as BaseUser;
use Propel\Runtime\Propel;
use Propel\Runtime\Connection\ConnectionInterface;

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

	public function preSave(ConnectionInterface $con = null)
	{
		return $this->validate();
	}

	public function preInsert(ConnectionInterface $con = null)
	{
		$this->setEmailConfirmToken(token(50));
		return true;
	}

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

	public function getPossibleToNames($part){
		$sql =  'SELECT id as data, nick as value FROM user WHERE nick LIKE :name
				UNION
				SELECT id as data, name as value FROM group_of_users LEFT JOIN user_group ON group_of_users.id = user_group.group_id WHERE user_id = :user_id AND user_group.rights >= 1 AND name LIKE :name';
		$con = Propel::getWriteConnection(\Models\Map\UserTableMap::DATABASE_NAME);
		$stmt = $con->prepare($sql);
		$stmt->execute(array('name' => $part.'%', 'user_id' => $this->getId()));
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function setNick($v){
		parent::setNick(htmlspecialchars(strip_tags($v), ENT_QUOTES, 'UTF-8'));
	}
}
