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
		$this->renderString($id);
	}
}