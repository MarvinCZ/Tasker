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
		$this->params['notes'] = NoteQuery::create()->
			filterByUser($this->params['user'])->
			leftJoinWith('Note.Category')->
			find();
		$this->params['notifications'] = NotificationQuery::create()->
			filterByUser($this->params['user'])->
			find();
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
		$this->params['categories'] = options_for_select($categories, -1);
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