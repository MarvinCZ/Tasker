<?php

namespace Controllers;

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

	protected function category(){
		$this->params['categories'] = $this->params['user']->getCategories();
		$this->params['colors'] = ['limet', 'green', 'darkgreen', 'aqua', 'blue', 'violet', 'purple', 'pink', 'red', 'darkorange', 'lightorange', 'yellow', 'sand'];
	}

	protected function groups(){
		$this->params['groups'] = $this->params["user"]->getUserGroupsJoinGroup();
		$this->params['rights_select'] = options_names_for_select(shareOptionsForSelect());
	}

	protected function group($id){
		$criteria = new Criteria();
		$criteria->add('user_group.group_id', $id, Criteria::EQUAL);
		$group = $this->params["user"]->getUserGroupsJoinGroup($criteria)->getFirst();
		if($group){
			$this->params['group'] = $group->getGroup();
			$this->params['relation'] = $group;
			$this->params['rights_select'] = options_names_for_select(shareOptionsForSelect($group->getRights()));
		}
		else{
			$this->addFlash('error', t('common.not_found'));
			redirectTo('/settings/groups');
		}
	}
}