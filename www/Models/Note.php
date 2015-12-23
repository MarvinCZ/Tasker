<?php

namespace Models;

use Models\Base\Note as BaseNote;
use \DateTime;

/**
 * Skeleton subclass for representing a row from the 'note' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Note extends BaseNote
{
	/**
	 * Formats \DateTime into string
	 * @return string deadline
	 */
	public function getFormatedDeadline(){
		return $this->getDeadline()->format("d. m. Y h:m");
	}

	/**
	 * Returns state
	 * @return string state
	 */
	public function getStateText(){
		return $this->getState();
	}

	/**
	 * Returns state compared with time
	 * @return string modified state
	 */
	public function getStateClass(){
		$days = null;
		$deadline = false;
		if($this->getDeadline()){
			$days = $this->getDeadLine()->getTimeStamp() - (new DateTime())->getTimeStamp();
			$days /= 86400;
			$deadline = true;
		}
		switch ($this->getState()) {
			case 'closed':
				return 'closed';
				break;
			case 'done':
				return 'ok';
				break;
			case 'opened':
				if(!$deadline || $days > 1)
					return 'ok';
				if($days > 0)
					return 'soon';
				return 'late';
				break;
			case 'wip':
				if(!$deadline || $days > 0)
					return 'soon';
				return 'late';
				break;
		}
	}

	/**
	 * @return string show path
	 */
	public function getShowPath(){
		return '/notes/' . $this->getId();
	}

	/**
	 * @return edit path
	 */
	public function getEditPath(){
		return '/notes/edit/' . $this->getId();
	}
}
