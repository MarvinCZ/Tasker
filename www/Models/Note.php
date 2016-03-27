<?php

namespace Models;

use Models\Base\Note as BaseNote;
use Models\SharedQuery;
use Models\CommentQuery;
use Models\FileQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Connection\ConnectionInterface;
use Helpers\ConfigHelper;
use \DateTime;
use \PDO;
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
	public function preSave(ConnectionInterface $con = null)
	{
		return $this->validate();
	}

	public function postInsert(ConnectionInterface $con = null)
	{
		$this->setLink(md5(uniqid($this->getId(), true)));
		$this->save();
		return true;
	}

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
		return t('models.note.states.' . $this->getState());
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
				return 'done';
				break;
			case 'opened':
				if(!$deadline || $days > 1)
					return 'opened';
				if($days > 0)
					return 'opened';
				return 'late';
				break;
			case 'wip':
				if(!$deadline || $days > 0)
					return 'wip';
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

	public function getShareLink(){
		if($this->getLink() == null){
			$this->setLink(md5(uniqid($this->getId(), true)));
			$this->save();
		}
		return ConfigHelper::getValue('app.url') . '/shared/note/' . $this->getLink();
	}

	public function getRightsForUser($user){
		if($this->getUser() == $user)
			return 3;
		$criteria = new Criteria();
		$criteria->add('user_note.user_id', $user->getId(), Criteria::EQUAL);
		$criteria->addDescendingOrderByColumn('user_note.rights');
		$acc = $this->getUserNotes($criteria)->getFirst();
		return $acc == null ? -1 : $acc ->getRights();
	}

	public function getSharedTo(){
		$sql =  "SELECT shared.id, shared.rights, shared.what_type, shared.to_id, shared.to_type, CASE WHEN user.id IS NULL THEN group_of_users.name ELSE user.nick END AS name, CASE WHEN user.id IS NULL THEN COUNT(group_user.id) ELSE 1 END AS user_count from note LEFT JOIN category ON (category.id=note.category_id) LEFT JOIN shared ON ((shared.what_id=category.id) AND (shared.what_type='category')) OR ((shared.what_id=note.id) AND (shared.what_type='note')) LEFT JOIN user ON (shared.to_id=user.id) AND (shared.to_type='user') LEFT JOIN group_of_users ON (shared.to_id=group_of_users.id) AND (shared.to_type='group') LEFT JOIN user_group ON (group_of_users.id=user_group.group_id) LEFT JOIN user AS group_user ON (user_group.user_id=group_user.id) WHERE note.id=? AND shared.id IS NOT NULL GROUP BY shared.id";
		$con = Propel::getWriteConnection(Map\NoteTableMap::DATABASE_NAME);
		$stmt = $con->prepare($sql);
		$stmt->execute(array($this->getId()));
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function delete(ConnectionInterface $con = null){
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(Map\NoteTableMap::DATABASE_NAME);
        }

        SharedQuery::create()->filterByNote($this)->delete();
        CommentQuery::create()->filterByNote($this)->delete();
        FileQuery::create()->filterByNote($this)->delete();

        parent::delete($con);
	}

	public function shareTo($to, $rights = 0){
		$share = new Shared();
		$share->setRights($rights);
		if(is_a($to, "Models\Group"))
			$share->setGroup($to);
		if(is_a($to, "Models\User"))
			$share->setUser($to);
		$this->addShared($share);		
	}

	public static function getTranslatedStates(){
		return translateArray(['opened', 'done', 'wip', 'closed'],'models.note.states');
	}
}
