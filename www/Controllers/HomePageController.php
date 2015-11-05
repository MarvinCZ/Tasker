<?php

namespace Controllers;

use Controllers\ApplicationController;
use Models\User;
use Models\UserQuery;
use Models\Note;
use Models\NoteQuery;
use Models\Category;
use Models\CategoryQuery;

class HomePageController extends ApplicationController{

	public function __construct(){
		parent::__construct();
		$this->beforeFilters['findUser'] = function(){
			$this->params['user'] = $user = UserQuery::create()->findPK(1);;
		};

	}
	protected function index(){
		//$a = "Text";
		//$this->params = array_merge($this->params, get_defined_vars()); $a will be available in view
		//$b = 35;
		//$this->params['c'] = $b; $b will be available in view as $c
	}

	protected function filter(){
		$user = $this->params['user'];
		$categories = CategoryQuery::create()->
			select('name')->
			filterByUser($user)->
			find();
		$options = options_for_select($categories, -1);
		$this->params['options'] = $options;
	}

}