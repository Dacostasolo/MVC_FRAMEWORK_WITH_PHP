<?php


namespace app\core;

use app\core\Application;


abstract class DbModel
{

    abstract public function tableName(): string;

    abstract public function attributes(): array;

    public function save()
    {
        $table = $this->tableName();
        $attributes = $this->attributes();

        $columns = implode(',', $attributes);
        $params = implode(',', array_map(fn ($attr) => ":$attr", $this->attributes()));

        $sql = "INSERT INTO $table ($columns) VALUES($params)";
        $stmt = $this->prepareStatement($sql);

        array_walk($attributes, fn ($attr) => $stmt->bindParam(":$attr", $this->{$attr}));

        $stmt->execute();
    }


    protected function prepareStatement($sql)
    {
        return Application::$app->database->pdo->prepare($sql);
    }
}
