<?php

namespace app\models;

use app\core\DbModel;

abstract class Model extends DbModel
{


    static string $IS_REQUIRED = 'required';
    static string $IS_EMAIL = 'email';
    static string $IS_MIN = 'min';
    static string $IS_MAX = 'max';
    static string $IS_MATCH = 'match';
    static int $STATUS_ACTIVE = 1;
    static int $STATUS_INACTIVE = 0;
    static int $STATUS_DELETED = -1;
    static string $IS_UNIQUE = 'unique';

    public array $errors = [];
    function loadData($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    abstract public function rules(): array;

    function validate()
    {

        $params = [];

        foreach ($this->rules() as $attribute => $values) {
            $results = $this->{$attribute};

            foreach ($values as  $value) {

                if (is_string($value)) {
                    $ruleName = $value;
                }

                if (is_array($value)) {
                    $ruleName = $value[0];
                    $params = $value;
                }

                if ($ruleName === self::$IS_REQUIRED && !$results) {
                    $this->addError($attribute, $ruleName, $params);
                }

                if ($ruleName === self::$IS_EMAIL && !filter_var($results, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($attribute, $ruleName, $params);
                }

                if ($ruleName === self::$IS_MIN && strlen($results) < $value[$ruleName]) {
                    $this->addError($attribute, $ruleName, $params);
                }

                if ($ruleName === self::$IS_MAX && strlen($results) > $value[$ruleName]) {
                    $this->addError($attribute, $ruleName, $params);
                }


                if (self::$IS_MATCH == $ruleName && $results !==  $this->{$value[$ruleName]}) {
                    $this->addError($attribute, $ruleName, $params);
                }


                if (self::$IS_UNIQUE == $ruleName) {

                    $sql = "SELECT count(*) FROM {$this->tableName()} where $attribute = '{$this->$attribute}';";
                    $stmt = $this->prepareStatement($sql);
                    $stmt->execute();

                    $results = $stmt->fetchColumn();

                    if ($results > 0) {
                        $this->addError($attribute, $ruleName, $params);
                    }
                }
            }
        }


        return empty($this->errors) && true;
    }


    function addError($attribute, $ruleName, $params)
    {
        $message = $this->errorMessage()[$ruleName] ?? "";

        foreach ($params as $key => $value) {
            $message = str_replace("{{{$key}}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }


    function errorMessage()
    {
        return [
            'required' => 'The field is required',
            'email' => 'must be a valid email address',
            'min' => 'must have a minimum of {{min}} characters',
            'max' => 'must have a maximum of {{max}} characters',
            'match' => 'mush much {{match}}',
            'unique' => 'this {{unique}} already exist'
        ];
    }

    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute)
    {
        return $this->errors[$attribute][0] ?? false;
    }
}
