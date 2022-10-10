<?php

namespace app\core;

class Application
{
    public static Application $app;
    public static string $ROOT_DIR;
    public Router $router;
    public Response $response;
    protected Request $request;
    public Database $database;
    public Controller $controller;
    public function __construct($rooPath, $config)
    {
        self::$ROOT_DIR = $rooPath;
        self::$app = $this;
        $this->response = new Response();
        $this->request = new Request();
        $this->router = new Router($this->request, $this->response);
        $this->database = new Database($config['db']);
    }

    public function run()
    {
        echo  $this->router->resolve();
    }

    function getController(): Controller
    {
        return $this->controller;
    }

    function setController(Controller $controller): self
    {
        $this->controller = $controller;
        return $this;
    }
}
