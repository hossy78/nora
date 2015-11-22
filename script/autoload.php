<?php
require_once __DIR__.'/../class/AutoLoader.php';
require_once __DIR__.'/../class/Nora.php';

// ノラクラスを読み込む
Nora\AutoLoader::register([
    'Nora' => __DIR__.'/../class'
]);
