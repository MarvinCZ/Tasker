
/**
 * Gets any ConstraintViolation objects that resulted from last call to validate().
 *
 *
 * @return     object ConstraintViolationList
 * @see        validate()
 */
public function getValidationFailures()
{
    return $this->validationFailures;
}

/**
 * Gets any ConstraintViolation objects that resulted from last call to validate().
 *
 *
 * @return     array
 * @see        validate()
 */
public function getValidationFailuresI18n()
{
	$f = $this->getValidationFailures();
	$array = array();
	foreach ($f as $fail) {
		array_push($array, $fail);
	}
	$a = array_map(function($fail){
		return array('path' => $fail->getPropertyPath(),'message' => t($fail->getMessage()));
	},$array);
	return $a;
}