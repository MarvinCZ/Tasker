<?php

use \DateTime;

//Returns string with html for select component
function select($title, $name, $multiple, $options, $required){
	$params = array('name' => strtolower($name),
					'display_name' => $title,
					'multiple' => $multiple,
					'options' => $options,
					'required' => $required);
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

//Returns string with html for select component
function datetime_picker($title, $name, $required, $options = array()){
	$value = isset($options['value']) ? $options['value'] : $required ? new DateTime() : null;
	$year = $value == null ? null : $value->format('Y');
	$month = $value == null ? null : $value->format('m');
	$day = $value == null ? null : $value->format('d');
	$hour = $value == null ? null : $value->format('h');
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