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

	public function index(){
		$this->renderToTemplate();
	}

	public function filtr(){
		$user = UserQuery::create()->findPK(1);
		$categories = CategoryQuery::create()->
			select('name')->
			filterByUser($user)->
			find();
		$options = options_for_select($categories, -1);
		$this->renderToTemplate(array('options' => $options));
	}

}