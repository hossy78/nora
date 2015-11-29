<?php
namespace Nora\Develop\Document;

use Nora;
use Nora\Base\Component\Componentable;
use Nora\Output;

use Nora\System\Context\Context;
use Nora\System\Reflection;
/**
 * ヘルプ
 */
class Help 
{
    private $_context;
    private $_debugger;

    public function __construct(Context $context)
    {
        $this->_context = $context;
        $this->_debugger = $context->getService('debugger');
        $this->_output = $context->getService('output');
    }

    public function __invoke($var)
    {
        if ((is_string($var) && !class_exists($var)) || is_array($var)  || is_numeric($var))
        {
            $this->_debugger->debug($var);
            return;
        }

        $rc = new Reflection\ReflectionClass($var);

        $out = $this->_output;

        $out->head('<link rel="stylesheet" href="/share/markdown/markdown.css">');

        // クラス参照を作成
        $rc = new Reflection\ReflectionClass($var);

        // クラス名
        $out->writeTitle("クラス名: ".$rc->getName());


        // 情報テーブル
        $info = $out->table('INFO');

        $tmp = $rc->getParentClass();
        while($tmp)
        {
            $info->add("継承", $tmp->getName());
            $tmp = $tmp->getParentClass();
        }

        $info->add("ファイル",$rc->getFileName());
        foreach($rc->getInterfaces() as $if)
        {
            $info->add("インターフェイス", $if->getName());
        }
        foreach($rc->getTraits() as $tr)
        {
            $info->add("トレイト", $tr->getName());
        }

        // リードミーが設定されていた場合それを表示する
        if ($rc->hasAttr('readme'))
        {
            foreach($rc->getAttr('readme') as $path)
            {
                if ($path[0] !== '.')
                {
                    $path = '/./'.$path;
                }else{
                    $path = '/'.$path;
                }
                $file = realpath(
                    dirname($rc->getFileName()).$path
                );
                $out->markdown(file_get_contents($file));
            }
        }


        // メソッドリストを作成
        $methods = $out->table('METHODS');
        $methods->add(
                'メッソド名',
                'コメント',
                '引数'
            );

        // Public Methodを取得する
        foreach($rc->getPublicMethods() as $m)
        {
            if (substr($m->getName(), 0, 1) === '_')
            {
                continue;
            }

            $methods->add(
                $m->getName(),
                $m->comment(),
                $m->toStringParams(),
                $m->getDeclaringClass()->getShortName()
            );
        }

        $out->writeSource($rc->getFileName());

        $out->send();
    }
}
