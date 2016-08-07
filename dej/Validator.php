<?php

namespace dej;
use \dej\App;

class Validator extends \dej\common\Singleton
{
    public $messages = [];
    public $locale;

    protected function __construct()
    {
        $this->locale = App::Config()->locale;
        $this->messages = require "app/locale/{$this->locale}/validation/messages.php";
    }


    public function validate($input = [], $rules = null)
    {
        if ($rules == null) throw new \Exception("rules cannot be empty in validate() method");
        if ($input == null) $input = [];
        //determine input type
        if(is_object($input))
        {
            return $this->validateObject($input, $rules);
        }
        elseif (is_array($input))
        {
            $inputObject = (object) $input;
            return $this->validateObject($inputObject, $rules);
        }
        else{
            return $this->validateValue($input, $rules);
        }
        

    }

    public function validateObject($input = null, $rules = null)
    {
        $allErrors = [];
        $fields = array_keys($rules);
        foreach ($fields as $field)
        {
            $errors = $this->validateValue($input->$field, $rules[$field]);
            if(!empty($errors)) $allErrors[$field] = $errors;
        }
        return $allErrors;
    }

    public function validateValue($input = null, $rules = null)
    {
        $rulesArray = explode('|', $rules);
        $errors = [];
        foreach ($rulesArray as $rule)
        {
            if(empty($rule)) continue;

            switch ($rule)
            {
                case 'required':
                    //array_push($errors, $this->validateRequired($input));
                    $this->pushIfNotFalse($errors, $this->validateRequired($input));
                    break;

                case 'string':
                    //array_push($errors, $this->validateString($input));
                    $this->pushIfNotFalse($errors, $this->validateString($input));

                    break;

                case 'int':
                    //array_push($errors, $this->validateInteger($input));
                    $this->pushIfNotFalse($errors, $this->validateInteger($input));
                    break;

                case (preg_match("/^min:/", $rule) === 1):
                    $min = explode(':', $rule);
                    //array_push($errors, $this->validateMin($input, $min[1]));
                    $this->pushIfNotFalse($errors, $this->validateMin($input, $min[1]));
                    break;

                case (preg_match("/^max:/", $rule) === 1):
                    $max = explode(':', $rule);
                    //array_push($errors, $this->validateMax($input, $max[1]));
                    $this->pushIfNotFalse($errors, $this->validateMax($input, $max[1]));
                    break;

                case 'email':
                    //array_push($errors, $this->validateEmail($input));
                    $this->pushIfNotFalse($errors, $this->validateEmail($input));
                    break;

                default:
                    throw new \Exception("Validation type: '$rule' is invalid.");
                    break;
            }
        }
        return $errors;
    }

    private function validateRequired($input)
    {
        if (empty($input)) return $this->getErrorMessage('required');
        else return false;
    }

    private function validateString($input)
    {
        if (!is_string($input)) return $this->getErrorMessage('string');
        else return false;
    }

    private function validateInteger($input)
    {
        if (!is_int($input)) return $this->getErrorMessage('int');
        else return false;
    }

    private function validateMin($input, $min)
    {

        $min = intval($min);

        if(is_string($input))
        {
            if (strlen($input) < $min) return $this->getErrorMessage('min', [$min]);
            else return false;
        }
        elseif (is_int($input))
        {
            if ($input < $min) return $this->getErrorMessage('min', [$min]);
            else return false;
        }
        else return true;

    }

    private function validateMax($input, $max)
    {
        $max = intval($max);

        if(is_string($input))
        {
            if (strlen($input) > $max) return $this->getErrorMessage('max', [$max]);
            else return false;
        }
        elseif (is_int($input))
        {
            if ($input > $max) return $this->getErrorMessage('max', [$max]);
            else return false;
        }
        else return true;

    }

    private function validateEmail($input)
    {
        if (filter_var($input, FILTER_VALIDATE_EMAIL) === false) return $this->getErrorMessage('email');
        else return false;
    }


    private function getErrorMessage($rule, $params = null)

    {
        return vsprintf($this->messages[$rule], $params);
    }

    private function pushIfNotFalse(&$array, $value)
    {
        if ($value != false)
        {
            array_push($array, $value);
            return true;
        }
        else return false;
    }

}