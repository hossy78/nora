<?php
namespace Nora\System\Validation\Rule;


class CallbackRule extends Base\RuleSingle
{
    public $cb;

    public function __construct($name, $cb)
    {
        $this->setName($name);
        $this->cb = $cb;
    }


    protected function _check($input)
    {
        return call_user_func($this->cb, $input);
    }
}
