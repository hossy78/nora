<?php
namespace Nora\Develop\Document;

use Nora;
use Nora\Base\Component\Componentable;
use Nora\Util\Reflection;
use Nora\Output;

/**
 * ヘルプ: コンポーネント
 */
class Help 
{
    use Componentable;

    public function initComponentImpl( )
    {
    }

    public function __invoke($var)
    {
        $out = $this->resolve('Output');

        if ((is_string($var) && !class_exists($var)) || is_array($var)  || is_numeric($var))
        {
            var_dump($var);
            return;
        }

        $out->println('<link rel="stylesheet" href="/share/markdown/markdown.css">');

        $rc = new Reflection\ReflectionClass($var);

        // ヘッダーの出力
        $out->println($rc->toString());

        if (method_exists($var, '__help'))
        {
            $out->println(">>>   FORWARDED by __help Method    <<<".PHP_EOL);
            return $var::__help($out);
        }


        // リードミーが設定されていた場合それを表示する
        if ($rc->hasAttr('readme'))
        {
            $paths = $rc->getAttr('readme');
            foreach($paths as $path)
            {
                if ($path[0] !== '.')
                {
                    $path = '/./'.$path;
                }else{
                    $path = '/'.$path;
                }
                $file = dirname($rc->getFileName()).$path;

                if ($out->isWeb())
                {
                    $out->printRaw(
                        '<b>README: '.$file.'</b>'.
                        $this->resolve('MarkDown')->parse(
                            file_get_contents(
                                realpath(
                                    dirname($rc->getFileName()).$path
                                )
                            )
                        )
                    );
                }else{
                    $out->printRaw(
                        file_get_contents(
                            realpath(
                                dirname($rc->getFileName()).$path
                            )
                        )
                    );
                }
            }
        }

        $list = [
            [
                'メッソド名',
                'コメント',
                '定義ファイル',
            ]
        ];
        foreach($rc->getPublicMethods() as $m)
        {
            if (substr($m->getName(), 0, 1) === '_')
            {
                continue;
            }
            if ($m->hasAttr('NoHelp')) continue;
            $list[] = '-+-+';
            $list[] = [
                $m->toString(),
                $m->comment(),
                sprintf('%s (%s,%s)', $m->getFileName(), $m->getStartLine(), $m->getEndLine())
            ];
        }
        $out->println($out->table($list));

        $out->printSource($rc->getFileName());
    }
}
