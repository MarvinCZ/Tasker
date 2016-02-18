<?php

namespace Controllers;

use Models\User;
use Models\UserQuery;
use Models\Group;
use Models\GroupQuery;

class GroupController extends ApplicationController{
	protected function add(){
		$group = new Group();
		$group->setName($_POST['name']);
		foreach ($_POST['user'] as $u) {
			$user = UserQuery::create()->
				filterByNick($u)->
				findOne();
			$group->addUserWithRights($user, 0);
		}
		$group->addUserWithRights($this->params['user'], 3);
		$group->save();
		redirectBack();
	}
}