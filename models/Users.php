<?php

namespace app\models;

class Users extends Model
{
    public string $firstName = "";
    public string $lastName = "";
    public string $email = "";
    public string $password = "";
    public string $confirmPassword = "";

    public int $status = 0;

    function tableName(): string
    {
        return 'users';
    }


    function attributes(): array
    {
        return ['firstName', 'lastName', 'email', 'password', 'status'];
    }

    function register()
    {
        $this->status = self::$STATUS_ACTIVE;
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->save();
        return true;
    }

    public function rules(): array
    {
        return
            [
                'firstName' => [self::$IS_REQUIRED],
                'lastName' => [self::$IS_REQUIRED],
                'email' => [self::$IS_REQUIRED, self::$IS_EMAIL, [self::$IS_UNIQUE, 'unique' => 'email']],
                'password' => [self::$IS_REQUIRED, [self::$IS_MIN, 'min' => 8], [self::$IS_MAX, 'max' => 24]],
                'confirmPassword' => [self::$IS_REQUIRED, [self::$IS_MATCH, 'match' => 'password'], [self::$IS_MIN, 'min' => 8], [self::$IS_MAX, 'max' => 24]]
            ];
    }
}
