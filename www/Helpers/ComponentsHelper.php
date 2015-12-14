<?php

use \DateTime;

//Returns string with html for select component
function select($title, $name, $multiple, $options, $required, $on_change = null){
	$params = array('name' => strtolower($name),
					'display_name' => $title,
					'multiple' => $multiple,
					'options' => $options,
					'required' => $required,
					'on_change' => $on_change);
	return renderToString('Views/Components/select.phtml',$params);
}

//Create array for select component
function options_for_select($array, $selected = null){
	if(!is_array($selected)){
		$selected = array($selected);
	}
	$options = array();
	for($i = 0; $i < count($array); $i++){
		array_push($options, array(
			'name' => strtolower($array[$i]),
			'display_name' => ucfirst($array[$i]),
			'selected' => in_array(strtolower($array[$i]), $selected) ? 'checked' : ''
			));
	}
	return $options;
}

//Returns string with html for select component
function datetime_picker($title, $name, $required, $options = array()){
	$value = isset($options['value']) ?
		(is_object($options['value']) ?
			$options['value'] :
			new DateTime($options['value'])):
		($required ?
			new DateTime():
			null);
	$year = $value == null ? null : $value->format('Y');
	$month = $value == null ? null : $value->format('m');
	$day = $value == null ? null : $value->format('d');
	$hour = $value == null ? null : $value->format('H');
	$minute = $value == null ? null : $value->format('i');
	$val = $value == null ? null : $value->format('Y-m-d h:i');
	$params = array('name' => strtolower($name),
					'display_name' => $title,
					'year' => $year,
					'month' => $month,
					'day' => $day,
					'hour' => $hour,
					'minute' => $minute,
					'value' => $val,
					'required' => $required);
	return renderToString('Views/Components/datetime_picker.phtml',$params);
}