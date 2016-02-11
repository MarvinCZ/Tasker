<?php

namespace Controllers;

use Controllers\ApplicationController;
use Models\User;
use Models\UserQuery;
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
		$can_change = false;
		if($s){
			if($s->getWhatType() == "note"){
				$note = NoteQuery::create()->
					filterById($s->getWhatId())->
					filterNotesForUser($this->params['user'], 2);
				if($note){
					$can_change = true;
				}
			}
		}
		if($can_change){
			$s->setRights($_POST['share_rights']);
			$s->save();
		}
		redirectBack();
	}
	
	protected function add_to_note($id){
		$note = NoteQuery::create()->findPK($id);
		$user = UserQuery::create()->
			filterByNick($_POST['name'])->
			findOne();
		if($note && $user){
			$rights = getUserRights($this->params['user'], $note);
			if($rights >= 2){
				$share = new Shared();
				$share->setUser($user);
				$share->setNote($note);
				$share->setRights($_POST['share_rights']);
				$share->save();
			}
			else{
				//nema prava
			}
		}
		else{
			//neexistuje
		}
		redirectTo('/notes/'.$id);	
	}
	
	protected function remove($id){
		$shared = SharedQuery::create()->findPK($id);
		$note = $shared->getNote();
		$rights = getUserRights($this->params['user'], $note);
		var_dump($rights);
		if($rights >= 2 && $shared->getRights() < $rights){
			$shared->delete();
		}
		redirectBack();
	}
}