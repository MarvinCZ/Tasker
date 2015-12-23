<?php

namespace Models;

use Propel\Runtime\Propel;
use Models\Base\NoteQuery as BaseNoteQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'note' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class NoteQuery extends BaseNoteQuery
{
	protected $fulltext_text;
	protected $full_text = false;

	/**
	 * Fulltext search
	 * @param  string searched keyword
	 * @return Models\NoteQuery this query
	 */
	public function filterByText($text){
		$this->full_text = true;
		$this->fulltext_text = $text;
		return $this->where('match(note.title, note.description) against (?)', $text);
	}

	/**
	 * Order result by relevance of fulltext search
	 * @return Models\NoteQuery this query
	 */
	public function orderByRelevance(){
		if(!$this->full_text)
			return $this;
		$against = Propel::getServiceContainer()->getReadConnection($this->getDbName())->quote($this->fulltext_text);
		return $this->withColumn('match (title) against (' . $against . ')', 's1')->
			withColumn('match(description) against (' . $against . ')', 's2')->
			addDescendingOrderByColumn("(s1*2)+s2");
	}

	/**
	 * Select only that notes, which can user access with givel level of access
	 * @param  Models\User user
	 * @param  integer level of access (0 - read, 1 - 0 + write, 2 - 1 + manage, 3 - owner)
	 * @return Models\NoteQuery this query
	 */
	public function filterNotesForUser($user, $rights = 0){
		if($rights <= 0)
			return $this->useUserNoteQuery()->filterByUser($user)->filterByRights(array('min' => $rights))->endUse();
		return $this->useUserNoteQuery()->filterByUser($user)->endUse();
	}

}
