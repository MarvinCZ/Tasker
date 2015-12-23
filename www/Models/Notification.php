<?php

namespace Models;

use Models\Base\Notification as BaseNotification;

/**
 * Skeleton subclass for representing a row from the 'notification' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Notification extends BaseNotification
{
	/**
	 * Returns origin of notification
	 * @return mixed(Models\Note, Models\User) origin
	 */
	public function getOrigin(){
		$type = $this->getOriginType();
		if($type == "user")
			return $this->getOriginUser();
		if($type == "note")
			return $this->getNote();
		return null;
	}

	/**
	 * Returns html for icon
	 * @return string icon
	 */
	public function getIcon(){
		switch ($this->getType()) {
			case 'info':
				return '<i class="fa fa-info-circle text-info"></i>';
			case 'warning':
				return '<i class="fa fa-exclamation-triangle text-warning"></i>';
			case 'request':
				return '<i class="fa fa-bell-o text-warning"></i>';
			case 'success':
				return '<i class="fa fa-check text-success"></i>';
		}
	}

	/**
	 * @return string path to notigication action
	 */
	public function getLink(){
		return "/";
	}
}
