<?php

namespace Controllers;

use Controllers\ApplicationController;
use Models\User;
use Models\UserQuery;
use Models\Identity;
use Models\IdentityQuery;
use Helpers\LogHelper;

class UserController extends ApplicationController{
	public function __construct(){
		parent::__construct();
		$this->addBeforeFilterExeption("is_logged", array('create', 'login', 'fb_login', 'g_login'));
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
			$response = $fb->get('/me?fields=id,email');
			$user = $response->getGraphUser();
			$identity = IdentityQuery::create()->
				filterByProvider('facebook')->
				filterByUid($user['id'])->
				joinWith('User')->
				findOne();
			if(!$identity){
				$identity = new Identity();
				$identity->setProvider('facebook');
				$identity->setUid($user['id']);
				$u = UserQuery::create()->
						filterByEmail($user['email'])->
						findOne();
				if(!$u){
					$response = $fb->get('/me?fields=email,name,picture.width(320)');
					$userNode = $response->getGraphUser();
					$u = new User();
					if(!$userNode['picture']['is_silhouette']){
						$picture = "";
						do {
							$picture = time().md5(rand(0,1000000000000)).'.jpg';
						} while (file_exists("Uploads/Avatars/".$picture));
						file_put_contents("Uploads/Avatars/".$picture, file_get_contents($userNode['picture']['url']));
						$u->setAvatarPath($picture);
					}
					$u->setNick($userNode['name']);
					$u->setEmail($userNode['email']);
					$u->setPassword(md5(rand(0,1000000000000)));
				}
				$identity->setUser($u);
				$identity->save();
			}
			$_SESSION['user'] = $identity->getUser()->getId();
			redirectTo("/notes");
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}
	}

	protected function g_login(){
		if(isset($_GET['code'])){
			$google_client = getGoogle();
			$google_client->authenticate($_GET['code']);
			$service = new \Google_Service_Oauth2($google_client);
			$info = $service->userinfo->get();
			$identity = IdentityQuery::create()->
				filterByProvider('google')->
				filterByUid($info->id)->
				joinWith('User')->
				findOne();
			if(!$identity){
				$identity = new Identity();
				$identity->setProvider('google');
				$identity->setUid($info->id);
				$u = UserQuery::create()->
						filterByEmail($info->email)->
						findOne();
				if(!$u){
					$u = new User();
					$picture = "";
					do {
						$picture = time().md5(rand(0,1000000000000)).'.jpg';
					} while (file_exists("Uploads/Avatars/".$picture));
					file_put_contents("Uploads/Avatars/".$picture, file_get_contents($info->picture));
					$u->setAvatarPath($picture);
					$u->setNick($info->name);
					$u->setEmail($info->email);
					$u->setPassword(md5(rand(0,1000000000000)));
				}
				$identity->setUser($u);
				$identity->save();
			}
			$_SESSION['user'] = $identity->getUser()->getId();
			redirectTo("/notes");
		}
	}
}