<?php

namespace Controllers;

use Models\Group;
use Models\GroupQuery;
use Models\User;
use Models\UserQuery;
use Propel\Runtime\ActiveQuery\Criteria;

class SettingsController extends ApplicationController{
	public function __construct(){		
		parent::__construct();
		$this->template = "Settings/template.phtml";
	}

	protected function index(){
	}

	protected function categories(){
		$this->params['categories'] = $this->params['user']->getCategories();
		$this->params['colors'] = ['limet', 'green', 'darkgreen', 'aqua', 'blue', 'violet', 'purple', 'pink', 'red', 'darkorange', 'lightorange', 'yellow', 'sand'];
	}

	protected function groups(){
		$this->params['groups'] = $this->params["user"]->getUserGroupsJoinGroup();
		$this->params['rights_select'] = options_names_for_select(Group::getTranslatedRights());
	}

	protected function group($id){
		$group = GroupQuery::create()->findPK($id);
		if($group && $relation = $group->getRelationWithUser($this->params["user"])){
			$this->params['group'] = $relation->getGroup();
			$this->params['relation'] = $relation;
			$this->params['rights_select'] = options_names_for_select($relation->getPossibleTranslatedRights());
		}
		else{
			$this->addFlash('error', t('common.not_found'));
			$this->redirectTo('/settings/groups');
		}
	}
}