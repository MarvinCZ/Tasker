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
	public function getOrigin(){
		$type = $this->getOriginType();
		if($type == "user")
			return $this->getOriginUser();
		if($type == "note")
			return $this->getNote();
		return null;
	}
}
