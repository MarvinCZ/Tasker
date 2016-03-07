<?php

//Extract params to be available for included file
function includeFile($file, $params = array()){
	extract($params);
	include($file);
}

//Same as includeFile, but insted of redering directli to output its redirected to string
function renderToString($file, $params = array()){
	extract($params);
	ob_start();
    include $file;
    return ob_get_clean();
}

//Smart link_to methond. Made to simplify views
function link_to($link, $text, $title, $params){
	$link = '<a href="' . $link . '" ';
	if($title)
		$link .= 'title="' . $title . '" ';
	else
		$link .= 'title="' . $text . '"';
	if(is_array($params)){
		foreach ($params as $param) {
			$link .= parse_params($param);
		}
	}
	else{
		$link .= parse_params($params);
	}
	$link .= '>' . $text . '</a>';
	return $link;
}

//Parse paremetrs passed to link_to
function parse_params($param) {
	if($param[0] == "."){
		$class = substr($param, 1, strlen($param));
		return ' class="' . $class . '"';
	}
	if($param[0] == "#"){
		$id = substr($param, 1, strlen($param));
		return ' id="' . $id . '"';
	}
	if($param[0] == "_"){
		return ' target="' . $param . '"';
	}
}

function array_keys_blacklist(array $array, array $keys) {
   foreach($array as $key => $value){
		if(in_array($key, $keys)){
			unset($array[$key]);
		}
	}
	return $array;
}

function snakeToCamel($val) {
	return str_replace(' ', '', ucwords(str_replace('_', ' ', $val)));
}

function arrayKeysSnakeToCamel($array){
	foreach (array_keys($array) as $key) {
		$transformedKey = snakeToCamel($key);
		$array[$transformedKey] = &$array[$key];
        unset($array[$key]);
	}
	return $array;
}

function stateOptions($selected){
	$arr = [];
	foreach (['opened', 'done', 'wip', 'closed'] as $value) {
		$arr[$value] = t('models.note.states.'.$value);
	}
	return options_names_for_select($arr, $selected);
}

function sharedToHTML($to){
	$html = '<div class="pull-left">';
	if($to['to_type'] == 'user'){
		$html .= '<a class="user-link" href="users/' . $to['to_id'] . '">' . $to['name'] . '</a> ';
	}
	else{
		$html .= '<a class="user-link" href="groups/' . $to['to_id'] . '">' . $to['name'] . ' (' . $to['user_count'] . ')' . '</a> ' ;
	}
	$html .= $to['what_type'] == "category" ? t('common.via_category') : t('common.via_note');
	$html .= '</div>';
	$html .= '<div class="pull-right">';
	$html .= t('rights.' . $to['rights']);
	$html .= '</div>';
	return $html;
}

function optionsForSelect($options, $selected = ''){
	$html = '';
	foreach ($options as $key => $value) {
		$html.='<option value="'.$key.'"'.($selected != $key ? '' : ' selected="selected"').'>'.$value.'</option>';
	}
	return $html;
}

function sharedToForm($to, $rights){
	if($to['rights']>$rights)
		return;
	$params = array();
	$params['target_link'] = $to['to_type'] == "user" ? 'users/' : 'groups/';
	$params['target_link'] .= $to['to_id'];
	$params['form_link'] = 'share/update/' . $to['id'];
	$params['options'] = options_names_for_select(shareOptionsForSelect($rights), $to['rights']);
	$params['rights'] = t('rights.' . $to['rights']);
	$params['name'] = $to['name'];
	if($to['to_type'] == 'group')
		$params['name'] .= ' (' . $to['user_count'] . ')';
	$params['id'] = $to['id'];
	return renderToString('Views/Note/_sharedto_form.phtml',$params);
}

function shareOptionsForSelect($max = 3){
	$arr = [];
	for ($i = 0; $i <= $max; $i++) { 
		$arr[$i] = t('rights.' . $i);
	}
	return array_slice($arr, 0, $max + 1);
}

function getFacebook(){
	return new Facebook\Facebook([
  'app_id' => '593319754150363',
  'app_secret' => Helpers\ConfigHelper::getValue('oauth.facebook.secret'),
  'default_graph_version' => 'v2.2',
  ]);
}

function getGoogle(){
	$client = new Google_Client();
	$client->setAuthConfigFile('Config/googlekey.json');
	$client->addScope("email");
	$client->addScope("profile");
	$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/google-callback');
	$client->setAccessType("offline");
	return $client;
}

function getUserRights($user, $note){
	$criteria = new Propel\Runtime\ActiveQuery\Criteria();
	$criteria->add('user_note.user_id', $user->getId(), Propel\Runtime\ActiveQuery\Criteria::EQUAL);
	$criteria->addDescendingOrderByColumn('user_note.rights');
	$acc = $note->getUserNotes($criteria)->getFirst();
	return $acc == null ? 0 : $acc ->getRights();	
}

function getUserRightsCategory($user, $category){
	$criteria = new Propel\Runtime\ActiveQuery\Criteria();
	$criteria->add('user_category.user_id', $user->getId(), Propel\Runtime\ActiveQuery\Criteria::EQUAL);
	$criteria->addDescendingOrderByColumn('user_category.rights');
	$acc = $category->getUserCategories($criteria)->getFirst();
	return $acc == null ? 0 : $acc ->getRights();	
}

function t($path, $params = array()){
	$path = str_replace('.', '_', $path);
	if($path[0] == '_'){
		$back = debug_backtrace();
		if(isset($back[1]['class'])){
			//from model or controller
			$path = str_replace('\\', '_', strtolower($back[1]['class'])) . $path;
			$path = str_replace('_base', '', $path);
		}
		if(isset($back[2]['function']) && $back[2]['function'] == 'includeFile'){
			//from view
			$data = explode('/', $back[2]['args'][0]);
			if(!isset($data[2])){
				$path = 'view_' . substr($data[1], 0, strpos($data[1], '.')) . $path;
			}
			else{
				$path = 'view_' . $data[1] . '_' . substr($data[2], 0, strpos($data[2], '.')) . $path;
			}
		}
	}
	$s = L::__callStatic($path, null);
	for ($i=0; $i < count($params); $i++) {
		$s = str_replace('#'.$i, $params[$i], $s);
	}
	return $s;
}

function translateArray($array, $base){
	$arr = [];
	foreach ($array as $value) {
		$arr[$value] = t($base . '.' . $value);
	}
	return $arr;
}