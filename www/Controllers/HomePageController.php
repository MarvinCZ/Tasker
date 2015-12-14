<?php

namespace Controllers;

use Controllers\ApplicationController;
use Models\User;
use Models\UserQuery;
use Models\Note;
use Models\NoteQuery;
use Models\Category;
use Models\CategoryQuery;
use Models\Comment;
use Models\CommentQuery;

class HomePageController extends ApplicationController{

	public function __construct(){
		parent::__construct();
		$this->addBeforeFilter(function(){
			if(isset($this->params['user'])){
				//redirectTo("/notes");
			}
		}, "redirect_if_logged");
	}
	protected function index(){
		//$a = "Text";
		//$this->params = array_merge($this->params, get_defined_vars()); $a will be available in view
		//$b = 35;
		//$this->params['c'] = $b; $b will be available in view as $c
	}

}