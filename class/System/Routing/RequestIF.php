<?php
namespace Nora\System\Routing;

interface RequestIF
{
    public function getMethod( );
    public function getMaskedPath($mask);
    public function setMatched($matched);
}
