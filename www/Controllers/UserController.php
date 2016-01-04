<?php

namespace Controllers;

use Controllers\ApplicationController;
use Models\User;
use Models\UserQuery;
use Helpers\LogHelper;

class UserController extends ApplicationController{
	public function __construct(){
		parent::__construct();
		$this->addBeforeFilterExeption("is_logged", array('create', 'login', 'fb_login'));
	}

	protected function show($id){

	}

	protected function create(){
		$params = arrayKeysSnakeToCamel($_POST['user']);
		if($_POST['user']['password'] != $_POST['user']['password2']){
			$this->addFlash("error", "passwords aint matching");
			redirectTo("/");
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
		if($user){
			$_SESSION['user'] = $user->getId();
			redirectTo("/notes");
		}
		else{
			$this->addFlash("error", "user does not exists or password is invalid");
			redirectTo("/");
		}
	}

	protected function logout(){
		$_SESSION['user'] = null;
		redirectTo("/");
	}

	protected function fb_login(){
		$fb = getFacebook();
		$helper = $fb->getRedirectLoginHelper();
		try {
			$accessToken = $helper->getAccessToken();
			$fb->setDefaultAccessToken($accessToken);
			$response = $fb->get('/me?fields=email,name,picture.width(300)');
			$userNode = $response->getGraphUser();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}

		if (isset($accessToken)) {
			$_SESSION['facebook_access_token'] = (string) $accessToken;
		}
		var_dump($userNode);
		var_dump($userNode['picture']);
		$this->renderString($accessToken);
	}
}