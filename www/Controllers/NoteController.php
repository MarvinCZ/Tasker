<?php

namespace Controllers;

use Controllers\ApplicationController;
use Models\User;
use Models\UserQuery;
use Models\Note;
use Models\NoteQuery;
use Models\Category;
use Models\CategoryQuery;
use Models\Notification;
use Models\NotificationQuery;
use Models\Comment;
use Models\CommentQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use \DateTime;

class NoteController extends ApplicationController{

	public function __construct(){
		parent::__construct();
	}

	protected function show_category($category){
		$this->params['deadline_to'] = null;
		$this->params['deadline_from'] = null;
		$this->params['fulltext'] = "";
		$this->params['importance'] = "0";

		$note_query = NoteQuery::create()->
			leftJoinWith('Note.Category');
		if(!isset($category)){
			$relation = isset($_GET['relation']) ? $_GET['relation'] : 'mine';
			switch ($relation) {
				case 'mine':
					$note_query = $note_query->filterByUser($this->params['user']);
					break;
				case 'editable':
					$note_query = $note_query->filterNotesForUser($this->params['user'], 1);
					break;
				default:
					$note_query = $note_query->filterNotesForUser($this->params['user']);
					break;
			}
			$this->params['relation'] = options_names_for_select(translateArray(['mine', 'editable', 'all'],'relations'), $relation);
		}
		$deadline_params = array();
		if(isset($_GET['deadline_to']) && !empty($_GET['deadline_to'])){
			$deadline_params['max'] = $_GET['deadline_to'];
			$this->params['deadline_to'] = $_GET['deadline_to'];
		}
		if(isset($_GET['deadline_from']) && !empty($_GET['deadline_from'])){
			$deadline_params['min'] = $_GET['deadline_from'];
			$this->params['deadline_from'] = $_GET['deadline_from'];
		}
		if(!empty($deadline_params)){
			$note_query = $note_query->filterByDeadline($deadline_params);
		}
		$this->params['category_only'] = false;
		if($category != null){
			$this->params['category'] = CategoryQuery::create()->findPK($category);
			if(!$this->params['category']){
				$this->addFlash('error', t('common.not_found'));
				$this->redirectTo('/');
			}
			$note_query = $note_query->
				filterByCategoryId($category);
			$this->params['category_only'] = true;
			$this->params['shared_to'] = $this->params['category']->getSharedTo();
			$rights = getUserRightsCategory($this->params['user'], $this->params['category']);
			$this->params['rights_select'] = options_names_for_select(shareOptionsForSelect($rights));
			$this->params['rights'] = $rights;
		}
		else if(isset($_GET['category']) && is_array($_GET['category'])){
			$note_query = $note_query->
			  	useCategoryQuery()->
    				filterByName($_GET['category'])->
  				endUse();
		}
		if(isset($_GET['state']) && is_array($_GET['state'])){
			$note_query = $note_query->
				filterByState($_GET['state']);
		}
		if(isset($_GET['importance_from']) && $_GET['importance_from'] > 0){
			$note_query = $note_query->
				filterByImportance(array('min' => $_GET['importance_from']));
			$this->params['importance'] = $_GET['importance_from'];
		}
		if(isset($_GET['fulltext']) && !empty($_GET['fulltext'])){
			$note_query = $note_query->
				filterByText($_GET['fulltext']);
			$this->params['fulltext'] = $_GET['fulltext'];
		}

		if(isset($_GET['sort_by'])){
			switch ($_GET['sort_by']) {
				case 'deadline':
					$note_query = $note_query->addAscendingOrderByColumn("deadline");
					break;
				case 'relevance':
					$note_query = $note_query->orderByRelevance();
					break;
				case 'importance':
					$note_query = $note_query->addDescendingOrderByColumn("importance");
					break;
				case 'category':
					$note_query = $note_query->addAscendingOrderByColumn("category.name");
					break;
				case 'state':
					$note_query = $note_query->addAscendingOrderByColumn("state");
					break;
			}
		}
		$note_query = $note_query->addDescendingOrderByColumn("note.created_at");
		$page = 1;
		if(isset($_GET['page']) && $_GET['page'] > 0){
			$page = $_GET['page'];
		}
		$per_page = 32;
		$this->params['notes'] = $note_query->paginate($page = $page, $maxPerPage = $per_page);
		$this->params['notifications'] = NotificationQuery::create()->
			filterByUser($this->params['user'])->
			find();
		if(!$this->params['category_only']){
			$categories = CategoryQuery::create()->
				select('name')->
				filterByUser($this->params['user'])->
				find();
			$selected  = isset($_GET['category']) ? $_GET['category'] : null;
			$this->params['categories'] = options_for_select($categories, $selected);
		}
		$selected  = isset($_GET['state']) ? $_GET['state'] : null;
		$this->params['states'] = options_names_for_select(Note::getTranslatedStates(), $selected);
		$selected  = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'relevance';
		$this->params['sort_by'] = options_names_for_select(translateArray(['created_at', 'deadline', 'relevance', 'importance', 'category', 'state'], 'models.note'), $selected);
		if(strpos($_SERVER['HTTP_ACCEPT'], 'text/javascript') !== FALSE){
			$this->renderType('js.phtml');
		}
		else{
			$this->renderFileToTemplate('Note/show_all.phtml');
		}
	}

	protected function show_all(){
		$this->show_category(null);
	}

	protected function show($id){
		$note = NoteQuery::create()->
			filterNotesForUser($this->params['user'])->
			leftJoinWith('Note.Category')->
			leftJoinWith('Note.Comment')->
			leftJoinWith('Comment.User')->
			leftJoinWith('Note.Shared')->
			condition('shared_join', '((shared.what_id=note.id) AND (shared.what_type="note")) OR ((shared.what_id=category.id) AND (shared.what_type="category"))')->
			setJoinCondition('Shared', 'shared_join')->
			addAscendingOrderByColumn("comment.created_at")->
			findPK($id);
		if($note){
			$this->params['note'] = $note;
			$rights = getUserRights($this->params['user'], $note);
			$this->params['rights_select'] = options_names_for_select(shareOptionsForSelect($rights));
			$this->params['rights'] = $rights;
			$this->params['shared_to'] = $this->params['note']->getSharedTo();
			$this->params['states'] = stateOptions($this->params['note']->getState());
		}
		else{
			$this->addFlash("error", t('common.not_found'));
			$this->redirectTo('/notes');
		}
	}

	protected function add(){
		$user = $this->params['user'];
		$categories = CategoryQuery::create()->
			select('name')->
			filterByUser($user)->
			find();
		$this->params['categories'] = options_for_select($categories);
		$this->params['note'] = new Note();
	}

	protected function edit($id){
		$this->params['note'] = NoteQuery::create()->
			filterNotesForUser($this->params['user'])->
			leftJoinWith('Note.Category')->
			findPK($id);
		$categories = CategoryQuery::create()->
			select('name')->
			filterByUser($this->params['user'])->
			find();
		$selected  = $this->params['note']->getCategoryId() ? $this->params['note']->getCategory()->getName() : null;
		$this->params['categories'] = options_for_select($categories, strtolower($selected));
	}

	protected function save($id){
		$params = $this->getAllowedKeysForEdit();
		$note = NoteQuery::create()->
			filterNotesForUser($this->params['user'])->
			leftJoinWith('Note.Category')->
			findPK($id);
		$note->fromArray($params);
		$category = $_POST['category'];
		$category = CategoryQuery::create()->
			filterByUser($this->params['user'])->
			filterByName($category)->
			findOne();
		if($category != null)
			$note->setCategory($category);
		if($note->save()){
			$this->addFlash("success", t('common.edited'));
			$this->renderString(json_encode(['redirect'=> $note->getShowPath()]));
		}
		else{
			$this->renderString(json_encode($note->getValidationFailuresI18n()));
		}
	}

	protected function create(){
		$params = $this->getAllowedKeysForCreate();
		$note = new Note();
		$note->fromArray($params);
		$category = isset($_POST['category']) ?
				CategoryQuery::create()->
				filterByUser($this->params['user'])->
				filterByName($_POST['category'])->
				findOne()
			: null;
		$note->setCategory($category);
		$note->setUser($this->params['user']);
		if($note->save()){
			$this->addFlash("success", t('common.added'));
			$this->renderString(json_encode(['redirect'=> $note->getShowPath()]));
		}
		else{
			$this->renderString(json_encode($note->getValidationFailuresI18n()));
		}
	}

	//TODO handle fail more ....
	protected function change_state($id){
		$note = NoteQuery::create()->
			filterNotesForUser($this->params['user'], 1)->
			findPK($id);
		if($note){
			$note->setState($_GET['selected']);
			$note->save();
			$this->renderString("status changed to " . $note->getState());
		}
		else{
			$this->renderString("You dont have enought rights");
			$log->addInfo('FAIL: user: ' . $this->params['user']->getId() . ' tryed to modify state of note: ' . $id);	
		}
	}

	protected function comment($id){
		$note = NoteQuery::create()->
			findPK($id);
		$rights = $note->getRightsForUser($this->params['user']);
		if($rights > 0){
			$comment = new Comment();
			$comment->setUser($this->params['user']);
			$comment->setNote($note);
			$comment->setText($_POST['message']);
			if($comment->save()){
				$this->renderString(json_encode(['redirect'=>'/notes/'.$id]));
			}
			else{
				$this->renderString(json_encode($comment->getValidationFailuresI18n()));
			}
		}
		else{
			$this->addFlash("error", t('common.no_rights'));
			$this->renderString(json_encode(['redirect'=>'/']));
		}
	}

	protected function remove($id){
		$note = NoteQuery::create()->
			findPK($id);
		$rights = $note->getRightsForUser($this->params['user']);
		if($rights >= 3){
			$note->delete();
			$this->addFlash("success", t('common.removed'));
		}
		else{
			$this->addFlash("error", t('common.no_rights'));
		}
		$pos = $this->getCallStackPositionWithout('/^[\/]notes[\/][0-9]+$/');
		$this->redirectBack($pos);
	}

	protected function getAllowedKeysForCreate(){
		$params = arrayKeysSnakeToCamel($_POST['note']);
		return array_intersect_key($params, array_flip(array('Title', 'Deadline', 'Description', 'State')));
	}

	protected function getAllowedKeysForEdit(){
		$params = arrayKeysSnakeToCamel($_POST['note']);
		return array_intersect_key($params, array_flip(array('Title', 'Deadline', 'Description', 'State')));
	}
}