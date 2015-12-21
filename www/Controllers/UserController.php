<?php

namespace Controllers;

use Controllers\ApplicationController;
use Models\User;
use Models\UserQuery;
use Helpers\LogHelper;

class UserController extends ApplicationController{
	public function __construct(){
		parent::__construct();
		$this->addBeforeFilterExeption("is_logged", array('create', 'login'));
	}

	protected function show($id){

	}

	protected function create(){
		$params = arrayKeysSnakeToCamel($_POST['user']);
		if($_POST['user']['password'] != $_POST['user']['password2']){
			$this->addFlash("error", "passwords aint matching");
			redirectTo("/#register");
		}
		$user = new User();
		$user->fromArray($params);
		$user->save();
		$this->addFlash("success", "registered");
		redirectTo("/");
	}

	protected function login(){
		$user = UserQuery::create()->
			filterByNick($_POST['user']['nick'])->
			filterByPassword($_POST['user']['password'])->
			findOne();
		$_SESSION['user'] = $user->getId();
		redirectTo("/notes");
	}

	protected function logout(){
		$_SESSION['user'] = null;
		redirectTo("/");
	}
}