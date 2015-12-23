<?php

namespace Models;

use Models\Base\Category as BaseCategory;
use Models\Shared;

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
	/**
	 * Return path to category's show page
	 * @return string path to show
	 */
	public function getShowPath(){
		return "/notes?&category[]=".$this->getName();
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
}
