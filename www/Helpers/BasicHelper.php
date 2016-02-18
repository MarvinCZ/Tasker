<?php
//redirect to location has to be called befor rendering (before controllers action ends, or before action calls render)
function redirectTo($location){
	header("Location: " . $location);
	die();
}

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
	return options_for_select(array('opened', 'done', 'wip', 'closed'), $selected);
}

function sharedToHTML($to){
	$html = '<div class="pull-left">';
	if($to['to_type'] == 'user'){
		$html .= '<a class="user-link" href="users/' . $to['to_id'] . '">' . $to['name'] . '</a>';
	}
	else{
		$html .= '<a class="user-link" href="groups/' . $to['to_id'] . '">' . $to['name'] . ' (' . $to['user_count'] . ')' . '</a>' ;
	}
	$html .= ' přes ';
	$html .= $to['what_type'] == "category" ? 'kategorii' : 'úkol';
	$html .= '</div>';
	$html .= '<div class="pull-right">';
	switch ($to['rights']) {
		case '0':
			$html .= 'pouze čtení';
			break;
		case '1':
			$html .= 'čtení, úprava';
			break;
		case '2':
			$html .= 'správa';
			break;
		case '3':
			$html .= 'majitel';
			break;
	}
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
	if($to['rights']>=$rights)
		return;
	$params = array();
	$params['target_link'] = $to['to_type'] == "user" ? 'users/' : 'groups/';
	$params['target_link'] .= $to['to_id'];
	$params['form_link'] = 'share/update/' . $to['id'];
	$params['options'] = shareOptionsForSelect($to['rights'], $rights);
	$params['rights'] = '';
	switch ($to['rights']) {
		case '0':
			$params['rights'] = 'pouze čtení';
			break;
		case '1':
			$params['rights'] = 'čtení, úprava';
			break;
		case '2':
			$params['rights'] = 'správa';
			break;
		case '3':
			$params['rights'] = 'majitel';
			break;
	}
	$params['name'] = $to['name'];
	if($to['to_type'] == 'group')
		$params['name'] .= ' (' . $to['user_count'] . ')';
	$params['id'] = $to['id'];
	return renderToString('Views/Note/_sharedto_form.phtml',$params);
}

function shareOptionsForSelect($selected = null, $max = 3){
	$arr = array(0=>'Pouze čtení', 1=>'Čtení, Úprava', 2=>'Správa', 3=>'Majitel');
	return array_slice($arr, 0, $max);
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
	return $note->getUserNotes($criteria)[0]->getRights();
}

function redirectBack(){
		header('Location: '.$_SERVER['HTTP_REFERER']);
		die();
}