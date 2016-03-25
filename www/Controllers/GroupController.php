<?php

namespace Controllers;

use Models\User;
use Models\UserQuery;
use Models\Group;
use Models\GroupQuery;
use Models\UserGroup;
use Models\UserGroupQuery;
use Models\Shared;
use Models\SharedQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;

class GroupController extends ApplicationController{

	//TODO validation check
	protected function add(){
		$group = new Group();
		$group->setName($_POST['name']);
		foreach ($_POST['user'] as $u) {
			$user = UserQuery::create()->
				filterByNick($u['name'])->
				findOne();
			$group->addUserWithRights($user, $u['rights']);
		}
		$group->addUserWithRights($this->params['user'], 3);
		$group->save();
		$this->redirectBack();
	}

	protected function add_user($id){
		$errors = [];
		$group = GroupQuery::create()->findPK($id);
		$user = UserQuery::create()->
			filterByNick($_POST['user'])->
			findOne();
		if(!$group){
			$this->addFlash("error", t('common.not_found'));
			$this->renderString(json_encode(['redirect'=>'/']));
			die();
		}
		$rights = $group->getRightsForUser($this->params["user"]);
		if($rights < 2 || $_POST['share_rights'] > $rights){
			$this->addFlash("error", t('common.no_rights'));
			$this->renderString(json_encode(['redirect'=>$_SERVER['HTTP_REFERER']]));
			die();			
		}
		if($user){
			$group->addUserWithRights($user, $_POST['share_rights']);
			try {
				$group->save();
			} catch (PropelException $e) {
				if($e->getPrevious()->getCode() == 23000){
					array_push($errors, ['path' => 'user', 'message' => t('models.category.validation.name.uniq')]);
				}
				else{
					throw $e;
				}
			}
		}
		else{
			$errors = [['path'=>'user', 'message'=>t('common.not_found')]];
		}
		if(empty($errors)){
			$this->addFlash("success", t('common.added'));
			$this->renderString(json_encode(['redirect'=>$_SERVER['HTTP_REFERER']]));
		}
		else{
			$this->renderString(json_encode($errors));
		}	
	}

	protected function edit_user($id){
		$group = GroupQuery::create()->findPK($id);
		$user = UserQuery::create()->findPK($_POST['user_id']);
		if(!$group || !$user){
			$this->addFlash("error", t('common.not_found'));
			$this->renderString(json_encode(['redirect'=>'/']));
			die();
		}
		if($group->canManage($this->params["user"])){
			$relation = $group->getRelationWithUser($user);
			//TODO check if relation exists
			$relation->setRights($_POST['user_rights']);
			$relation->save();
			$this->addFlash("success", t('common.edited'));
			$this->renderString(json_encode(['redirect'=>$_SERVER['HTTP_REFERER']]));
		}
		else{
			$this->addFlash("error", t('common.no_rights'));
			$this->renderString(json_encode(['redirect'=>'/']));
		}
	}

	protected function remove_user($id, $userid){
		$user = UserQuery::create()->findPK($userid);
		$group = GroupQuery::create()->findPK($id);
		if(!$group || !$user){
			$this->addFlash("error", t('common.not_found'));
			$this->redirectTo('/');
		}		
		if($group->canManage($this->params["user"])){
			$group->removeUser($user);
			$group->save();
			$this->addFlash("success", t('common.removed'));
			$this->redirectBack();
		}
		else{
			$this->addFlash("error", t('common.no_rights'));
			$this->redirectTo('/');
		}
	}

	protected function remove($id){
		$group = GroupQuery::create()->findPK($id);
		if(!$group){
			$this->addFlash("error", t('common.not_found'));
			$this->redirectTo('/');
		}		
		if($group->isOwner($this->params["user"])){
			$group->delete();
			$this->redirectBack();
		}
		else{
			$this->addFlash("error", t('common.no_rights'));
			$this->redirectTo('/');
		}		
	}

	protected function leave($id){
		$group = GroupQuery::create()->findPK($id);
		if(!$group){
			$this->addFlash("error", t('common.not_found'));
			$this->redirectTo('/');
		}
		$group->leave($this->params["user"]);
		$this->addFlash("success", t('.group_leaved'));
		$this->redirectBack();
	}
}