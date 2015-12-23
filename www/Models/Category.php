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
	public function getShowPath(){
		return "/notes?&category[]=".$this->getName();
	}

	public function share($to){
		$s = new Shared();
		if(is_a($to, "Models\Group"))
			$s->setGroup($to);
		if(is_a($to, "Models\User"))
			$s->setUser($to);
		$this->addShared($s);
	}
}
