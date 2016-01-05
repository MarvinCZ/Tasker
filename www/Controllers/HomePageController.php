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
use Models\Shared;
use Models\SharedQuery;
use Models\UserGroup;
use Models\UserGroupQuery;
use Models\Group;
use Models\GroupQuery;

class HomePageController extends ApplicationController{

	public function __construct(){
		parent::__construct();
		$this->addBeforeFilter("redirect_if_logged");
		$this->addBeforeFilterExeption("is_logged", "index");
		$this->addBeforeFilterExeption("redirect_if_logged", "test");
	}

	protected function redirect_if_logged(){
		if($this->params['user_logged'])
			redirectTo("/notes");
	}

	protected function index(){
		$fb = getFacebook();
		$helper = $fb->getRedirectLoginHelper();
		$permissions = ['email', 'public_profile'];
		$this->params['fblogin'] = $helper->getLoginUrl('http://' . $_SERVER['HTTP_HOST'] . '/fb-login-callback', $permissions);
	}
}