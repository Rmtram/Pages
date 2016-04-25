<?php

namespace Rmtram\Pages\System\Controllers;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

abstract class AbstractController implements ControllerProviderInterface
{
    /**
     * @var array
     */
    protected $routes = [];

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {

        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        if (method_exists($this, 'initialize')) {
            $this->initialize($app);
        }

        foreach ($this->routes as $method => $route) {
            if (preg_match('/^get|post|put|patch|delete/', $method, $match)) {
                $requestMethod = $match[0];
                if (!method_exists($this, $method)) {
                    throw new \BadMethodCallException(
                        sprintf('undefined method in %s@%s', get_called_class(), $method)
                    );
                }
                $controllers->$requestMethod($route, [$this, $method]);
            }
        }

        return $controllers;
    }

    /**
     * @return string
     */
    protected function now()
    {
        return date('Y-m-d H:i:s');
    }

}