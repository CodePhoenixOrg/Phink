<?php
namespace Phink\Rest;

use \Phink\Core\TStaticObject;


class TCustomRouter extends TStaticObject {

    use THttpTransport;
    
    //put your code here
    protected $apiName = '';
    protected $className = '';
    protected $baseNamespace = '';
    protected $apiFileName = '';
    protected $parameter = '';
    protected $translation = '';

    public function __construct($parent)
    {
        
        $this->authentication = $parent->getAuthentication();
        $this->request = $parent->getRequest();
        $this->response = $parent->getResponse();
        if($parent instanceof \Phink\Rest\TRestRouter) {
            $this->translation = $parent->translation;
            $this->requestType = $parent->requestType;
            $this->parameters = $parent->parameters;
            $this->className = $parent->className;

        } else {
            //(parent);
            $this->translation = '';
            $this->requestType = REQUEST_TYPE_WEB;
            $this->className = '';
            $this->parameters = null;
        }

        $this->mimetype = '';
        $this->encoding = '';
        $this->routes = null;
    }

    public function match() {
        $result = REQUEST_TYPE_WEB;
        
        if ($this->routes()) {

            foreach($this->routes as $reqtype) {
                $methods = $this->routes[$reqtype];
                $method = strtolower(REQUEST_METHOD);

                if (isset($methods[$method])) {
                    $routes = $methods[$method];
                    $url = REQUEST_URI;
                    foreach($routes as $key=>$value) {
                        $matches = ereg_replace($key, $value, $url);

                        if (matches !== url) {
                            $this->requestType = $reqtype;
                            $this->translation = $matches;
                            $baseurl = parse_url($this->translation, PHP_URL_QUERY);
                            $this->className = pathinfo($baseurl);
                            
//                            $this->parameters = $this->parameters || {};
//                            Object.assign($this->parameters, baseurl.query);

                            return $reqtype;
                        }
                    }
                }

            }
        }

        if ($this->translation === '') {
            $this->requestType = REQUEST_TYPE_WEB;
            $result = REQUEST_TYPE_WEB;
        }

        return $result;
    }

    public function routes() {
        $_routes = \Phink\Core\TRegistry::item('routes');

        if ($_routes) {
            if (file_exists(DOCUMENT_ROOT . 'routes.json')) {
                $_routes = file_get_contents(DOCUMENT_ROOT . 'routes.json');
            }
            if (strlen($_routes) > 0) {
                $_routes = json_decode($_routes, true);
                foreach($route as $key=>$value) {
                    \Phink\Core\TRegistry::write('routes', $key, $value);
                }
            }
        }

        $this->routes = $_routes;

        return $_routes;
    }

    public function translate() {}

    public function dispatch() {}
}


