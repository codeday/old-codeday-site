<?php

/* /var/www/new.codeday.org/app/config/../views/index.twig */
class __TwigTemplate_c3f354ea6bd4e720418bae4c36002433fadda0e08d2d540f072738477ed443e8 extends TwigBridge\Twig\Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("template.twig");

        $this->blocks = array(
            'splash' => array($this, 'block_splash'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "template.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_splash($context, array $blocks = array())
    {
        // line 3
        echo "    ";
        $this->env->loadTemplate("parts/index-splash.twig")->display($context);
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "    ";
        $this->env->loadTemplate("parts/faq.twig")->display($context);
        // line 7
        echo "    ";
        $this->env->loadTemplate("parts/schedule.twig")->display($context);
        // line 8
        echo "    ";
        $this->env->loadTemplate("parts/pitch.twig")->display($context);
        // line 9
        echo "    ";
        $this->env->loadTemplate("parts/sponsors-list.twig")->display($context);
    }

    public function getTemplateName()
    {
        return "/var/www/new.codeday.org/app/config/../views/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  49 => 9,  46 => 8,  43 => 7,  40 => 6,  37 => 5,  32 => 3,  29 => 2,);
    }
}
