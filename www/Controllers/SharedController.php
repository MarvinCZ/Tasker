<?php

namespace Controllers;

use Propel\Runtime\Propel;
use Controllers\ApplicationController;
use Models\User;
use Models\UserQuery;
use Models\Group;
use Models\GroupQuery;
use Models\Note;
use Models\NoteQuery;
use Models\Category;
use Models\CategoryQuery;
use Models\Notification;
use Models\NotificationQuery;
use Models\Comment;
use Models\CommentQuery;
use Models\Shared;
use Models\SharedQuery;

class SharedController extends ApplicationController{
	protected function update($id){
		$s = SharedQuery::create()->findPK($id);
		if($s->canChange($this->params['user'])){
			$s->setRights($_POST['share_rights']);
			$s->save();
		}
		$this->redirectBack();
	}
	
	protected function add_to_note($id){
		$note = NoteQuery::create()->findPK($id);
		$this->share_to($note, $_POST['name'], $_POST['share_rights']);
		$this->redirectTo('/notes/'.$id);
	}
	
	protected function add_to_category($id){
		$category = CategoryQuery::create()->findPK($id);
		$this->share_to($category, $_POST['name'], $_POST['share_rights']);
		$this->redirectTo('/category/'.$id);
	}

	protected function share_to($what, $name, $new_rights){
		$to = UserQuery::create()->
			filterByNick($name)->
			findOne();
		if(!$to){
			$to = GroupQuery::create()->
				filterByName($name)->
				findOne();
		}
		if($what && $to){
			$rights = $what->getRightsForUser($this->params['user']);
			if($rights >= 2 && $rights >= $new_rights){
				$what->shareTo($to, $new_rights);
				$what->save();
				$this->addFlash('success', t('share.shared'));
			}
			else{
				$this->addFlash('error', t('common.no_rights'));
			}
		}
		else{
			$this->addFlash('error', t('common.not_found'));
		}
	}

	protected function new_group(){
		if(isset($_POST['note_id'])){
			$item = NoteQuery::create()->
					filterById($_POST['note_id'])->
					findOne();
		}
		if(isset($_POST['category_id'])){
			$item = CategoryQuery::create()->
					filterById($_POST['category_id'])->
					findOne();
		}
		if($item && $rights = $item->getRightsForUser($this->params['user'])){
			if($rights >= 2 && $rights >= $_POST['share_rights']){
				$group = new Group();
				$group->setName($_POST['name']);
				if(isset($_POST['user'])){
					foreach ($_POST['user'] as $u) {
						$user = UserQuery::create()->
							filterByNick($u)->
							findOne();
						$group->addUserWithRights($user, 0);
					}
				}
				$group->addUserWithRights($this->params['user'], 3);
				$item->shareTo($group, $_POST['share_rights']);
				$item->save();
			}
		}
		$this->redirectBack();
	}
	
	protected function remove($id){
		$shared = SharedQuery::create()->findPK($id);
		if($shared->canChange($this->params['user'])){
			$shared->delete();
		}
		$this->redirectBack();
	}

	protected function possible(){
		$pos = $this->params['user']->getPossibleToNames($_GET['query']);
		$this->renderString(json_encode(array('suggestions' =>$pos)));
	}
}