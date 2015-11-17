<?php
/**
 * Opulence
 *
 * @link      https://www.opulencephp.com
 * @copyright Copyright (C) 2015 David Young
 * @license   https://github.com/opulencephp/Opulence/blob/master/LICENSE.md
 */
namespace Opulence\Routing\Routes;

use SuperClosure\Analyzer\AstAnalyzer;
use SuperClosure\Serializer;
use Opulence\Http\Requests\Request;

/**
 * Defines a list of routes that can be used by a router
 */
class RouteCollection
{
    /** @var array The list of methods */
    private static $methods = [
        Request::METHOD_DELETE,
        Request::METHOD_GET,
        Request::METHOD_POST,
        Request::METHOD_PUT,
        Request::METHOD_HEAD,
        Request::METHOD_OPTIONS,
        Request::METHOD_PATCH
    ];
    /** @var array The list of methods to their various routes */
    private $routes = [];
    /** @var ParsedRoute[] The mapping of route names to routes */
    private $namedRoutes = [];

    public function __construct()
    {
        foreach (self::$methods as $method) {
            $this->routes[$method] = [];
        }
    }

    /**
     * Gets the list of methods
     *
     * @return array The list of methods
     */
    public static function getMethods()
    {
        return self::$methods;
    }

    /**
     * Performs a deep clone of the routes
     */
    public function __clone()
    {
        foreach ($this->routes as $method => $routesByMethod) {
            foreach ($routesByMethod as $index => $route) {
                $this->routes[$method][$index] = clone $route;
            }
        }
    }

    /**
     * Prepares the controller closures to be serialized
     *
     * @return array The list of properties to store
     */
    public function __sleep()
    {
        $serializer = new Serializer(new AstAnalyzer());

        foreach ($this->routes as $method => $routesByMethod) {
            /** @var ParsedRoute $route */
            foreach ($routesByMethod as $route) {
                if ($route->usesClosure()) {
                    $route->setControllerClosure($serializer->serialize($route->getController()));
                }
            }
        }

        return array_keys(get_object_vars($this));
    }

    /**
     * Prepares the controller closures to be unserialized
     */
    public function __wakeup()
    {
        $serializer = new Serializer(new AstAnalyzer());

        foreach ($this->routes as $method => $routesByMethod) {
            /** @var ParsedRoute $route */
            foreach ($routesByMethod as $route) {
                if ($route->usesClosure()) {
                    $route->setControllerClosure($serializer->unserialize($route->getController()));
                }
            }
        }
    }

    /**
     * Adds a route to the collection
     *
     * @param ParsedRoute $route The route to add
     */
    public function add(ParsedRoute $route)
    {
        foreach ($route->getMethods() as $method) {
            $this->routes[$method][] = $route;

            if (!empty($route->getName())) {
                $this->namedRoutes[$route->getName()] =& $route;
            }
        }
    }

    /**
     * Gets all the routes
     *
     * @param string|null $method If specified, the list of routes for that method will be returned
     *      If null, all routes will be returned, keyed by method
     * @return ParsedRoute[] The list of routes
     */
    public function get($method = null)
    {
        if ($method === null) {
            return $this->routes;
        } elseif (isset($this->routes[$method])) {
            return $this->routes[$method];
        } else {
            return [];
        }
    }

    /**
     * Gets the route with the input name
     *
     * @param string $name The name to search for
     * @return ParsedRoute|null The route with the input name if one existed, otherwise null
     */
    public function getNamedRoute($name)
    {
        if (isset($this->namedRoutes[$name])) {
            return $this->namedRoutes[$name];
        }

        return null;
    }
}