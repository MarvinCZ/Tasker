<?php
//redirect to location has to be called befor rendering (before controllers action ends, or before action calls render)
function redirectTo($location){
	header("Location: " . $location);
	die();
}

//Extract params to be available for included file
function includeFile($file, $params){
	extract($params);
	include($file);
}

//Same as includeFile, but insted of redering directli to output its redirected to string
function renderToString($file, $params){
	extract($params);
	ob_start();
    include $file;
    return ob_get_clean();
}

//Returns string with html for select component
function select($name, $multiple, $options){
	$params = array('name' => strtolower($name),
					'display_name' => ucfirst($name),
					'multiple' => $multiple,
					'options' => $options);
	return renderToString('Views/Components/select.phtml',$params);
}

//Create array for select component
function options_for_select($array, $selected = null){
	$options = array();
	for($i = 0; $i < count($array); $i++){
		array_push($options, array(
			'name' => strtolower($array[$i]),
			'display_name' => ucfirst($array[$i]),
			'selected' => $array[$i] == $selected || ($selected == -1 && $i == 0) ? 'checked' : ''
			));
	}
	return $options;
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