<?php

namespace Models;

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
	public function filterByText($text){
		$this->full_text = true;
		$this->fulltext_text = $text;
		return $this->where('match(note.title, note.description) against (?)', $text);
	}

	public function orderByRelevance(){
		if(!$this->full_text)
			return $this;
		$against = sprintf(' against ("%s")', $this->fulltext_text);
		return $this->withColumn('match(title)' . $against, 's1')->
			withColumn('match(description)' . $against, 's2')->
			addDescendingOrderByColumn("(s1*2)+s2");
	}

}
