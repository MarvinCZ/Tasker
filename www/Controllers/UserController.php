<?php

namespace Controllers;

use Controllers\ApplicationController;
use Models\User;

class UserController extends ApplicationController{
	protected function show($id){

	}

	protected function add(){

	}

	protected function create(){
		$params = arrayKeysSnakeToCamel($_POST['user']);
		$user = new User();
		$user->fromArray($params);
		$user->save();
		$this->addFlash("success", "registered");
		redirectTo("/");
	}
}