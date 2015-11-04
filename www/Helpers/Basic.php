<?php

function includeFile($file, $params){
	extract($params);
	include($file);
}

function renderToString($file, $params){
	extract($params);
	ob_start();
    include $file;
    return ob_get_clean();
}

function select($name, $multiple, $options){
	$params = array('name' => strtolower($name),
					'display_name' => ucfirst($name),
					'multiple' => $multiple,
					'options' => $options);
	return renderToString('Views/Components/select.phtml',$params);
}

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