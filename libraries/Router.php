<?php
/**
 * Router Class
 * Handles URL routing
 */
class Router {
    protected $controller = 'Pages';
    protected $method = 'index';
    protected $params = [];
    protected $routes = [];

    /**
     * Add a route to the routing table
     * @param string $route The route URL pattern
     * @param string $target The controller/method target (e.g., 'Messages/index')
     */
    public function add($route, $target) {
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\(([^\)]+)\)/', '($1)', $route);
        $route = '^' . $route . '$';
        $this->routes[$route] = $target;
    }

    public function __construct() {
        $url = $this->getUrl();
        $urlPath = implode('/', $url);

        // Check for static files
        $filePath = APPROOT . '/public/' . $urlPath;
        if (file_exists($filePath) && is_file($filePath)) {
            header('Content-Type: ' . mime_content_type($filePath));
            readfile($filePath);
            exit;
        }

        // Check for registered routes
        foreach ($this->routes as $route => $target) {
            if (preg_match('/' . $route . '/', $urlPath, $matches)) {
                list($controller, $method) = explode('/', $target);
                $this->controller = ucwords($controller);
                $this->method = $method;
                $this->params = array_slice($matches, 1);

                if (file_exists('controllers/' . $this->controller . '.php')) {
                    require_once 'controllers/' . $this->controller . '.php';
                    if (class_exists($this->controller)) {
                        $this->controller = new $this->controller;
                        if (method_exists($this->controller, $this->method)) {
                            call_user_func_array([$this->controller, $this->method], $this->params);
                            return;
                        }
                    }
                }
            }
        }

        // Fallback to default URL parsing
        if (isset($url[0]) && file_exists('controllers/' . ucwords($url[0]) . '.php')) {
            $this->controller = ucwords($url[0]);
            unset($url[0]);
        } else {
            $this->controller = 'Pages';
        }

        if (!file_exists('controllers/' . $this->controller . '.php')) {
            $this->controller = 'Pages';
        }
        require_once 'controllers/' . $this->controller . '.php';

        if (!class_exists($this->controller)) {
            $this->controller = 'Pages';
            require_once 'controllers/Pages.php';
        }
        $this->controller = new $this->controller;

        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        $this->params = $url ? array_values($url) : [];

        if (method_exists($this->controller, $this->method)) {
            call_user_func_array([$this->controller, $this->method], $this->params);
        } else {
            $this->controller = new Pages;
            $this->method = 'index';
            $this->params = [];
            call_user_func_array([$this->controller, $this->method], $this->params);
        }
    }

    public function getUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return ['pages', 'index'];
    }
}