<?php
namespace Nora\System\Routing;

interface RouterIF
{
    public function route(RequestIF $req);
    public function hasNext();
    public function next();
}
