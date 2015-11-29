<?php
namespace Nora\Develop\Debug;

/**
 * 打倒 var_dump
 */
class Dumper
{
    public static function dump($var, $return = false, $options = [])
    {
        $options = array_merge([
            'depth' => 5,
            'level' => 0,
            'html' => false,
            'indent' => "  ",
        ], $options);

        $text = '';
        if ($options['html'] === true)
        {
            $text .= '<pre class="dumper-wrap">'.PHP_EOL;
        }
        $text.= self::dumpVar($var, $options);
        $text.= PHP_EOL;
        if ($options['html'] === true)
        {
            $text.= '</pre>';
        }
        if ($return === true)
        {
            return $text;
        }
        echo $text;
    }

    /**
     * ダンプ
     */
    private static function dumpVar($var, $options)
    {
        if (method_exists(__CLASS__, $m='dump'.gettype($var)))
        {
            return self::$m($var, $options);
        }
        return 'unknown('.$m.')';
    }

    private static function  dumpString($var, $options)
    {
        return trim(sprintf('string(%d) %s', strlen($var), $var));
    }

    private static function  dumpObject($var, $options)
    {
        if ($var instanceof \Closure)
        {
            return self::dumpClosure($var, $options);
        }

        return self::dumpClass($var, $options);
    }

    private static function dumpClosure($var, $options)
    {
        return 'Closure';
    }

    private static function dumpInteger($var, $options)
    {
        return sprintf('int(%d)', $var);
    }


    /**
     * Public members: member_name
     * Protected memebers: \0*\0member_name
     * Private members: \0Class_name\0member_name
     */
    private static function dumpClass($var, $options)
    {
        static $list;


        $rc = new \ReflectionClass($var);
        $key = spl_object_hash($var);

        if (isset($list[$key]))
        {
            return '*Recursion*'.get_class($var).'['.$key.']';
        }else{
            $list[$key] = true;
        }

        // プロパティを取得
        // Public members: member_name
        // Protected memebers: \0*\0member_name
        // Private members: \0Class_name\0member_name
        $props = (array) ($var);

        $text = 'Object '. get_class($var). '['.spl_object_hash($var).']';
        if ($options['level'] >= $options['depth'])
        {
            return $text;
        }


        $options['level'] += 1;
        foreach($props as $k=>$v)
        {
            $parts = explode("\x00", $k);
            if(count($parts) === 1)
            {
                $ac = 'public';
                $name = $parts[0];
            }elseif($parts[1] === '*'){
                $ac = 'protected';
                $name = $parts[2];
            }else{
                $ac = 'private';
                $name = $parts[2];
            }

            $text.= sprintf(PHP_EOL.str_repeat($options['indent'], $options['level'])."%s '%s' => %s", $ac, $name, self::dumpVar($v, $options));
        }


        return $text;

        /*
        $list[spl_object_hash($var)] = true;

        return get_class($var);
        return get_class($var).self::dumpArray((array) $var, $indent+1);
         */
    }

    private static function dumpArray($var, $options)
    {
        $text = sprintf('array (size=%s)', count($var));

        if ($options['level'] >= $options['depth'])
        {
            return $text;
        }


        $options['level'] += 1;
        foreach($var as $k=>$v)
        {
            $text.=sprintf(
                PHP_EOL.str_repeat(" ", $options['level'])."%s => %s",
                $k,
                self::dumpVar($v, $options)
            );
        }
        return trim($text);
    }

    private static function dumpNull( )
    {
        return 'null';
    }

    private static function dumpBoolean($var)
    {
        return $var ? 'true': 'false';
    }

    private static function dumpResource($var)
    {
        return 'Resource#'.(int) $var;
    }

    private static function indent($indent)
    {
        return str_repeat(" ", $indent);
    }

    private static function findLocation( )
    {
        foreach(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) as $trace)
        {
            var_dump($trace);
            continue;
            if (isset($trace['class']) && $trace['class'] == __CLASS__)
            {
                $location = $trace;
                continue;
            }
            elseif (isset($trace['function']))
            {
                try {
                    $ref = isset($trace['class'])
                        ? new \ReflectionMethod($trace['class'], $trace['function'])
                        : new \ReflectionFunction($trace['function']);
                    if ($ref->isInternal())
                    {
                        $location = $trace;
                    }
                } catch (\ReflectionException $e) {
                }

                break;
            }

            if (isset($location['file'], $location['line']) && is_file($location['file'])) {
                $lines = file($location['file']);
                $line = $lines[$location['line'] - 1];
                return array($location['file'], $location['line']);
            }
        }
    }
}
