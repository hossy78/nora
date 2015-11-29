<?php
namespace Nora\System\Routing;

/**
 * ルートオブジェクト
 *
 */
interface RouteIF
{
    /**
     * マッチ
     */
    public function match(RequestIF $req);
}
