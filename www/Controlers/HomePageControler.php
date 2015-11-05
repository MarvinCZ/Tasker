<?php

namespace Controlers;

use Controlers\ApplicationControler;
use Models\User;
use Models\UserQuery;
use Models\Note;
use Models\NoteQuery;
use Models\Category;
use Models\CategoryQuery;

class HomePageControler extends ApplicationControler{

	public function __construct(){
		parent::__construct();
		$this->beforeFilters['findUser'] = function(){
			$this->params['user'] = $user = UserQuery::create()->findPK(1);;
		};

	}
	protected function index(){
		$this->renderToTemplate();
	}

	protected function filter(){
		$user = $this->params['user'];
		$categories = CategoryQuery::create()->
			select('name')->
			filterByUser($user)->
			find();
		$options = options_for_select($categories, -1);
		$this->renderToTemplate(array('options' => $options));
	}

}