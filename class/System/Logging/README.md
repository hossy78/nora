ログ処理用のライブラリ
======================

## 構成
Logger
    - Handler
    - Filter
    - Handler

## 起動1

```php
$logger = Logger::create('Nora');

// ハンドラ作成
$handler = Handler::create([
        'type'  => 'stream',
        'path'  => 'php://stderr',
        'level' => 'warning'
]);

$logger = Logger::create('Nora');
$logger->addHandler($handler);
```

## 起動2

```php
$logger =  Logger::build([
    'name' => 'NoraFile',
    'handlers' => [
        [
            'type' => 'stream',
            'path' => '/tmp/nora.%(time|Y-m-d).%(user).log',
            'level' => 'warning'
        ]
    ]
]);
```

## 実行

```php
$logger->debug("デバッグ");
$logger->info("インフォ");
$logger->notice("通知");
$logger->warning("注意");
$logger->err("エラー");
$logger->alert("警告");
$logger->emerg("緊急");
```

## プロセス

processerを指定しておくと、ログの書き込み前に処理を走らせる事が出来る

```php
$logger =  Logger::build([
    'name' => 'NoraFile',
    'handlers' => [
        [
            'type' => 'stream',
            'path' => '/tmp/nora.%(time|Y-m-d).%(user).log',
            'level' => 'warning'
            'processer' => [
                function($log) {
                    $log['hoge'] = 'huga';
                },
                function($log) {
                    $log['ua'] = 'UserAgent';
                },
            ]
        ]
    ]
]);
```
