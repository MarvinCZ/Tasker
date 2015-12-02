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
use \DateTime;

class NoteController extends ApplicationController{
	protected function show_all(){
		$this->params['deadline_to'] = null;
		$this->params['fulltext'] = "";
		$this->params['importance'] = "0";

		$note_query = NoteQuery::create()->
			filterByUser($this->params['user'])->
			leftJoinWith('Note.Category');

		if(isset($_GET['deadline_to']) && !empty($_GET['deadline_to'])){
			$note_query = $note_query->filterByDeadline(array('max' => $_GET['deadline_to']));
			$this->params['deadline_to'] = $_GET['deadline_to'];
		}
		if(isset($_GET['category']) && is_array($_GET['category'])){
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
			}
		}
		$note_query = $note_query->addAscendingOrderByColumn("note.created_at");
		$page = 1;
		if(isset($_GET['page']) && $_GET['page'] > 0){
			$page = $_GET['page'];
		}
		$per_page = 32;
		$this->params['notes'] = $note_query->paginate($page = $page, $maxPerPage = $per_page);
		$this->params['notifications'] = NotificationQuery::create()->
			filterByUser($this->params['user'])->
			find();

		$categories = CategoryQuery::create()->
			select('name')->
			filterByUser($this->params['user'])->
			find();
		$selected  = isset($_GET['category']) ? $_GET['category'] : null;
		$this->params['categories'] = options_for_select($categories, $selected);
		$selected  = isset($_GET['state']) ? $_GET['state'] : null;
		$this->params['states'] = options_for_select(array('opened', 'done', 'wip', 'closed'), $selected);
		$selected  = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'relevance';
		$this->params['sort_by'] = options_for_select(array('created_at', 'deadline', 'relevance', 'importance', 'category'), $selected);
		if(strpos($_SERVER['HTTP_ACCEPT'], 'text/javascript') !== FALSE){
			$this->renderType('js.phtml');
		}
	}

	protected function show($id){
		$this->params['note'] = NoteQuery::create()->
			filterByUser($this->params['user'])->
			leftJoinWith('Note.Category')->
			findPK($id);
	}

	protected function add(){
		$user = $this->params['user'];
		$categories = CategoryQuery::create()->
			select('name')->
			filterByUser($user)->
			find();
		$this->params['categories'] = options_for_select($categories);
	}

	protected function create(){
		$params = arrayKeysSnakeToCamel($_POST['note']);
		$note = new Note();
		$note->fromArray($params);
		$category = $_POST['category'];
		$category = CategoryQuery::create()->
			filterByUser($this->params['user'])->
			filterByName($category)->
			findOne();
		if($category != null)
			$note->setCategory($category);
		$note->setUser($this->params['user']);
		$note->save();
		$this->addFlash("success", "Note added");
		redirectTo("/notes");
	}
}