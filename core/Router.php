<?php

namespace app\core;

use app\controller\SiteController;
use app\core;


class Router
{
    protected $routes = array();
    protected Request $request;
    protected Response $response;



    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {
        # code...
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            Application::$app->response->setResponse(404);
            return $this->renderView('_404');
        }

        if (is_string($callback)) {
            return $this->renderView($callback);
        }

        // Application::$app->setController(new $callback[0]);
        // $callback[0] = Application::$app->getController();

        if (is_array($callback)) {
            Application::$app->controller = new $callback[0];
            $callback[0] = Application::$app->controller;
            return call_user_func($callback, $this->request);
        }

        return call_user_func($callback);
    }


    public function renderView($view, $params = [])
    {


        $layoutContent = $this->layoutContent();
        $viewContent = $this->getOnlyView($view, $params);

        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    private function layoutContent()
    {
        $layout = Application::$app->controller->layout;
        ob_start();
        include_once Application::$ROOT_DIR . "/view/layouts/$layout.php";
        return ob_get_clean();
    }

    private function getOnlyView($view, $params)
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once Application::$ROOT_DIR . "/view/$view.php";
        return ob_get_clean();
    }
}
