<?php


use app\core\Application;


class m00001_initialize
{
    public function up()
    {

        $tb = Application::$app->database->create("signin");
        $tb->setField("username")->setType("varchar(255)");
        $tb->setField("email")->setType("varchar(100)");
        $tb->setField("password")->setType("varchar(50)");
        $tb->exec();
        // $tb->setField("")->setType("int auto_increment");
    }

    public function down()
    {
        echo "dropping migrations" . PHP_EOL;
    }
}
