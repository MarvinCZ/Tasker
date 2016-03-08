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

	public function getTranslatedRights(){
		return t('rights.'.$this->getRights());
	}

	public function canChange($user){
		return $this->hasRights($user, 2);
	}

	public function hasRights($user, $rights){
		if($this->getWhatType() == "note"){
			$note = NoteQuery::create()->
				filterById($this->getWhatId())->
				filterNotesForUser($user, $rights);
			if($note){
				return true;
			}
		}
		if($this->getWhatType() == "category"){
			$category = CategoryQuery::create()->
				filterById($this->getWhatId())->
				filterCategoriesForUser($user, $rights);
			if($category){
				return true;
			}
		}
		return false;		
	}
}
