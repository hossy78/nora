```php

$b = $context->getService('validator');

$v = $b
    ->offset('controller', $b->string())
    ->offset('name', $b->string())
    ->offset('passwd', $b->notEmpty()->string()->callback('a', function ($input) {
        return 'HOGE';
    }), [
        'NOTEMPTY' => '%(key)は空です',
        'INT' => '%(key): "%(input)" は数字ではありません',
        'DEFAULT' => 'パスワードエラー',
        'A' => [
            'HOGE' => 'ほげ'
        ]
    ]);

$v->assert([
    'controller' => 'hoge',
    'name' => "hajime",
    'passwd' => 1
], $out);

Nora::getService('debugger')->debug($out);
```
