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
		if($this->can_change($s)){
			$s->setRights($_POST['share_rights']);
			$s->save();
		}
		$this->redirectBack();
	}
	
	protected function add_to_note($id){
		$note = NoteQuery::create()->findPK($id);
		$user = UserQuery::create()->
			filterByNick($_POST['name'])->
			findOne();
		if($note){
			$rights = getUserRights($this->params['user'], $note);
			if($rights >= 2){
				$share = new Shared();
				$share->setRights($_POST['share_rights']);
				$share->setNote($note);
				$ok = false;
				if($user){
					$share->setUser($user);
					$ok = true;
				}
				else{
					$group = GroupQuery::create()->
						filterByName($_POST['name'])->
						findOne();
					if($group){
						$share->setGroup($group);
						$ok = true;
					}
				}
				if($ok){
					$share->save();
				}
				else{
					//neexistuje
				}
			}
			else{
					//nema prava
			}
		}
		else{
			//note neexistuje
		}
		$this->redirectTo('/notes/'.$id);
	}
	
	protected function add_to_category($id){
		$category = CategoryQuery::create()->findPK($id);
		$user = UserQuery::create()->
			filterByNick($_POST['name'])->
			findOne();
		if($category){
			$rights = getUserRightsCategory($this->params['user'], $category);
			if($rights >= 2){
				$share = new Shared();
				$share->setRights($_POST['share_rights']);
				$share->setCategory($category);
				$ok = false;
				if($user){
					$share->setUser($user);
					$ok = true;
				}
				else{
					$group = GroupQuery::create()->
						filterByName($_POST['name'])->
						findOne();
					if($group){
						$share->setGroup($group);
						$ok = true;
					}
				}
				if($ok){
					$share->save();
				}
				else{
					//neexistuje
				}
			}
			else{
					//nema prava
			}
		}
		else{
			//note neexistuje
		}
		$this->redirectTo('/category/'.$id);
	}

	protected function new_group(){
		$note = true;
		if(isset($_POST['note_id'])){
			$item = NoteQuery::create()->
					filterById($_POST['note_id'])->
					filterNotesForUser($this->params['user'], 2)->
					findOne();
		}
		elseif(isset($_POST['category_id'])){
			$item = CategoryQuery::create()->
					filterById($_POST['category_id'])->
					filterCategoriesForUser($this->params['user'], 2)->
					findOne();
			$note = false;
		}
		if($item){
			$share = new Shared();
			$share->setRights($_POST['share_rights']);
			$share->setWhat($item);
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
			$share->setGroup($group);
			$share->save();
		}
		$this->redirectBack();
	}
	
	protected function remove($id){
		$shared = SharedQuery::create()->findPK($id);
		if($this->can_change($shared)){
			$shared->delete();
		}
		$this->redirectBack();
	}

	protected function possible(){
			$sql =  'SELECT id as data, nick as value FROM user WHERE nick LIKE :name
								UNION
								SELECT id as data, name as value FROM group_of_users LEFT JOIN user_group ON group_of_users.id = user_group.group_id WHERE user_id = :user_id AND user_group.rights >= 1 AND name LIKE :name';
			$con = Propel::getWriteConnection(\Models\Map\UserTableMap::DATABASE_NAME);
			$stmt = $con->prepare($sql);
			$stmt->execute(array('name' => $_GET['query'].'%', 'user_id' => $this->params['user']->getId()));
			$pos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			$this->renderString(json_encode(array('suggestions' =>$pos)));
	}

	private function can_change($s, $rights = 2){
		if($s->getWhatType() == "note"){
			$note = NoteQuery::create()->
				filterById($s->getWhatId())->
				filterNotesForUser($this->params['user'], $rights);
			if($note){
				return true;
			}
		}
		if($s->getWhatType() == "category"){
			$category = CategoryQuery::create()->
				filterById($s->getWhatId())->
				filterCategoriesForUser($this->params['user'], $rights);
			if($category){
				return true;
			}
		}
		return false;
	}
}