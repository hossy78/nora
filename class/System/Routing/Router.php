<?php
namespace Nora\System\Routing;

class Router implements RouterIF
{
    private $_routes;
    private $_idx = 0;
    private $_mask = null;

    public function route(RequestIF $req)
    {
        for($i=$this->_idx;$i<count($this->_routes);$i++)
        {
            $route = $this->_routes[$i];

            if ($route->match($req, $this->_mask))
            {
                return $route;
            }
        }
        return false;
    }

    public function addRoute(RouteIF $route)
    {
        $this->_routes[] = $route;
    }

    static public function createRouter( )
    {
        return new Router;
    }

    public function createRoute($path, $callback)
    {
        return new Route($path, $callback);
    }

    public function map($path, $spec)
    {
        $this->addRoute(
            $this->createRoute($path, $spec)
        );
        return $this;
    }

    public function next( )
    {
        $this->_idx += 1;
    }

    public function hasNext( )
    {
        return (count($this->_routes) > $this->_idx);
    }

    public function mask($mask)
    {
        $this->_mask = $mask;
        return $this;
    }
}
