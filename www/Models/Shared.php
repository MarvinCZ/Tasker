<?php

namespace Models;

use Models\Base\Shared as BaseShared;
use Models\Category;
use Models\Note;

/**
 * Skeleton subclass for representing a row from the 'shared' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Shared extends BaseShared
{
	public function setWhat($item){
		if ($item instanceof Note){
			$this->setNote($item);
		}
		elseif ($item instanceof Category) {
			$this->setCategory($item);
		}

	}

}
