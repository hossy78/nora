<?php

require_once __DIR__.'/../class/AutoLoader.php';

// ノラクラスを読み込む
Nora\AutoLoader::register([
    'Nora' => __DIR__.'/../class'
]);

define('TEST_DIR', __DIR__);
