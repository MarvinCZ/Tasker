<?php

namespace Controllers;

use Models\User;
use Models\UserQuery;

class SettingsController extends ApplicationController{
	public function __construct(){		
		parent::__construct();
		$this->template = "Settings/template.phtml";
	}

	protected function index(){
	}

	protected function category(){
		$this->params['categories'] = $this->params['user']->getCategories();
	}
}