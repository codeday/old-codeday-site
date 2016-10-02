<?php

namespace CodeDay\Extensions;

class LangExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('i18n', [$this, 'i18n'], ['is_safe' => ['html']]),
        ];
    }

    public function i18n($key, $args = [])
    {
        return \trans($key, $args);
    }

    public function getName()
    {
        return 'lang_extension';
    }
}
