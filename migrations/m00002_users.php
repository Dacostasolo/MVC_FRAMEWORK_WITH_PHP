<?php

use app\core\Application;

class m00002_users
{

    function up()
    {

        $tb = Application::$app->database->create('users');

        $tb->setField('firstName')->setType('varchar(255)');
        $tb->setField('lastName')->setType('varchar(255)');
        $tb->setField('email')->setType('varchar(255)');
        $tb->setField('password')->setType('varchar(255)');
        $tb->setField('status')->setType('int');

        $tb->exec();
    }

    function down()
    {
    }
}
