<?php
namespace Nora\I18n;

class Translator
{
    public function Message($tpl, $params)
    {
        return vsprintf($tpl, $params);
    }
}
