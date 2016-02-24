<?php
namespace Propel\Generator\Behavior\ValidateI18n;

use Propel\Generator\Behavior\Validate\ValidateBehavior;

class ValidateI18nBehavior extends ValidateBehavior{
    /**
     * Add loadValidatorMetadata() method
     *
     * @return string
     */
    protected function addLoadValidatorMetadataMethod()
    {
        $params = $this->getParameters();
        $constraints = [];

        foreach ($params as $key => $properties) {
            if (!isset($properties['column'])) {
                throw new InvalidArgumentException('Please, define the column to validate.');
            }

            if (!isset($properties['validator'])) {
                throw new InvalidArgumentException('Please, define the validator constraint.');
            }

            if (!class_exists("Symfony\\Component\\Validator\\Constraints\\".$properties['validator'], true)) {
                if (!class_exists("Propel\\Runtime\\Validator\\Constraints\\".$properties['validator'], true)) {
                    throw new ConstraintNotFoundException('The constraint class '.$properties['validator'].' does not exist.');
                } else {
                    $classConstraint = "Propel\\Runtime\\Validator\\Constraints\\".$properties['validator'];
                }
            } else {
                $classConstraint = "Symfony\\Component\\Validator\\Constraints\\".$properties['validator'];
            }

            if (isset($properties['options'])) {
                if (!is_array($properties['options'])) {
                    throw new InvalidArgumentException('The options value, in <parameter> tag must be an array');
                }

                $options = array();
                foreach ($properties['options'] as $key => $value) {
                    if(preg_match('/(M|m)essage/', $key))
                        $value = 'models_'.$this->getTable()->getName().'_validation_'.$value;
                    $options[$key] = $value;
                }
                $opt = var_export($options, true);
                $opt = str_replace("\n", '', $opt);
                $opt = str_replace('  ', '', $opt);
                $properties['options'] = $opt;
            }

            $constraints[] = $properties;
            $this->builder->declareClass($classConstraint);
        }

        return $this->renderTemplate('objectLoadValidatorMetadata', ['constraints' => $constraints]);
    }
}