<?php

namespace app\core\form;

use app\models\Model;


class Form
{
    public static Model $model;

    public static function begin(string $action, string $method, $model)
    {

        self::$model = $model;
        echo sprintf(
            '<form action = "%s" method = "%s" class="p-5 mb-4 w-50 " >',
            $action,
            $method
        );

        return new Form;
    }

    public static function end()
    {
        echo '</form>';
    }



    public  function field(string $attribute)
    {
        return new Field(self::$model, $attribute);
    }
}
