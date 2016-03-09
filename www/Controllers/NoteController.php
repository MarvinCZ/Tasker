<?php

namespace Controllers;

use Controllers\ApplicationController;
use Models\User;
use Models\UserQuery;
use Models\Note;
use Models\NoteQuery;
use Models\NoteFilter;
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
		$filter = new NoteFilter($this->params['user'], true);
		$filter->sortBy(get('sort_by'))->
			filterByCategory($category)->
			filterByState(get('state'))->
			filterByFulltext(get('fulltext'))->
			filterByDeadline(get('deadline_from'), get('deadline_to'))->
			filterByImportanceFrom(get('importance_from'))->
			setPage(get('page'));

		if($filter->hasErrors()){
			foreach ($filter->getErrors() as $error) {
				$this->addFlash('error', $error);
			}
			$this->redirectTo('/');
		}

		$this->params = array_merge($this->params, $filter->getData());

		$this->params['shared_to'] = $this->params['category']->getSharedTo();
		$this->params['rights'] = $this->params['category']->getRightsForUser($this->params['user']);
		$this->params['rights_select'] = options_names_for_select(shareOptionsForSelect($this->params['rights']));

		if($this->isAJAXRequest()){
			$this->renderFile('Note/show_all.js.phtml');
		}
		else{
			$this->renderFileToTemplate('Note/show_all.phtml');
		}
	}

	protected function show_all(){
		$filter = new NoteFilter($this->params['user']);
		$filter->sortBy(get('sort_by'))->
			filterByState(get('state'))->
			filterByFulltext(get('fulltext'))->
			filterByDeadline(get('deadline_from'), get('deadline_to'))->
			filterByRelation(get('relation'))->
			filterByCategories(get('category'))->
			filterByImportanceFrom(get('importance_from'))->
			setPage(get('page'));

		$this->params = array_merge($this->params, $filter->getData());

		if($this->isAJAXRequest()){
			$this->renderFile('Note/show_all.js.phtml');
		}
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