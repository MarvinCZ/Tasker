<?php

namespace Models;

use Models\Note;
use Models\NoteQuery;
use Models\Category;
use Models\CategoryQuery;

class NoteFilter{
	private $query;
	private $params;
	private $user;
	private $errors;

	public function __construct($user, $category_only = false){
		$this->user = $user;
		$this->query = NoteQuery::create()->
			leftJoinWith('Note.Category');
		$this->params['category_only'] = $category_only;
		$this->params['deadline_to'] = null;
		$this->params['deadline_from'] = null;
		$this->params['fulltext'] = "";
		$this->params['importance'] = "0";
		$this->params['relation'] = "mine";
		$this->params['states'] = null;
		$this->params['categories'] = null;
		$this->params['sort_by'] = 'relevance';
		$this->params['page'] = 1;
		$this->errors = [];
	}

	public function getQuery(){
		return $this->query->
			addDescendingOrderByColumn("note.created_at");
	}

	public function getData(){
		$ret = [];
		foreach ($this->params as $k => $v) {
			$ret[$k] = $v;
		}
		$ret['relation'] = options_names_for_select(translateArray(['mine', 'editable', 'all'],'relations'), $ret['relation']);
		$ret['states'] = options_names_for_select(Note::getTranslatedStates(), $ret['states']);
		$ret['sort_by'] = options_names_for_select(translateArray(['created_at', 'deadline', 'relevance', 'importance', 'category', 'state'], 'models.note'), $ret['sort_by']);
		$categories = CategoryQuery::create()->
			select('name')->
			filterByUser($this->user)->
			find();
		$ret['categories'] = options_for_select($categories, $this->params['categories']);
		$ret['notes'] = $this->paginate($this->params['page']);
		return $ret;
	}

	public function getErrors(){
		return $this->errors;
	}

	public function hasErrors(){
		return !empty($this->errors);
	}

	public function sortBy($by){
		if(isset($by)){
			switch ($by) {
				case 'deadline':
					$this->query->addAscendingOrderByColumn("deadline");
					break;
				case 'relevance':
					$this->query->orderByRelevance();
					break;
				case 'importance':
					$this->query->addDescendingOrderByColumn("importance");
					break;
				case 'category':
					$this->query->addAscendingOrderByColumn("category.name");
					break;
				case 'state':
					$this->query->addAscendingOrderByColumn("state");
					break;
			}
			$this->params['sort_by'] = $by;
		}
		return $this;
	}

	public function filterByState($states){
		if(isset($states) && is_array($states)){
			$this->query->filterByState($states);
			$this->params['states'] = $states;
		}
		return $this;
	}

	public function filterByFulltext($text){
		if(isset($text) && !empty($text)){
			$this->query->filterByText($text);
			$this->params['fulltext'] = $text;
		}
		return $this;
	}

	public function filterByDeadline($from, $to){
		$deadline_params = array();
		if(isset($to) && !empty($to)){
			$deadline_params['max'] = $to;
			$this->params['deadline_to'] = $to;
		}
		if(isset($from) && !empty($from)){
			$deadline_params['min'] = $from;
			$this->params['deadline_from'] = $from;
		}
		if(!empty($deadline_params)){
			$this->query->filterByDeadline($deadline_params);
		}
		return $this;
	}

	public function filterByRelation($relation){
		if(!$this->params['category_only']){
		$this->params['relation'] = isset($relation) ? $relation : $this->params['relation'];
			switch ($this->params['relation']) {
				case 'mine':
					$this->query->filterByUser($this->user);
					break;
				case 'editable':
					$this->query->filterNotesForUser($this->user, 1);
					break;
				default:
					$this->query->filterNotesForUser($this->user);
					break;
			}
		}
		return $this;
	}

	public function filterByCategory($category){
		if($this->params['category_only']){
			$this->params['category'] = CategoryQuery::create()->findPK($category);
			if(!$this->params['category']){
				array_push($this->errors, t('common.not_found'));
				return $this;
			}
			$this->query->filterByCategory($this->params['category']);
		}
		return $this;
	}

	public function filterByCategories($categories){
		if(!$this->params['category_only'] && isset($categories) && is_array($categories)){
			$this->query->
				useCategoryQuery()->
					filterByName($categories)->
				endUse();
			$this->params['categories'] = $categories;
		}
		return $this;
	}

	public function filterByImportanceFrom($importance){
		if(isset($importance) && $importance > 0){
			$this->query->filterByImportance(array('min' => $importance));
			$this->params['importance'] = $importance;
		}
		return $this;
	}

	public function setPage($page){
		if($page != null && $page > 0){
			$this->params['page'] = $page;
		}
	}

	public function paginate($page){
		$page = ($page == null || $page <= 0) ? 1 : $page;
		$per_page = 32;
		return $this->query->paginate($page = $page, $maxPerPage = $per_page);

	}
}