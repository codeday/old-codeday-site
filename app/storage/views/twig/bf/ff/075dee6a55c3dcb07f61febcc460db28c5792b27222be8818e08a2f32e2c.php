<?php

/* parts/index-splash.twig */
class __TwigTemplate_bfff075dee6a55c3dcb07f61febcc460db28c5792b27222be8818e08a2f32e2c extends TwigBridge\Twig\Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div id=\"splash\">

    <div id=\"splash-info\" class=\"text-center\">
        <!-- Mobile nav -->
        <div class=\"nav-mobile hide-for-medium-up\">
            <nav class=\"site-nav text-right\">
                <ul>
                    ";
        // line 8
        $this->env->loadTemplate("parts/navigation.twig")->display($context);
        // line 9
        echo "                </ul>
            </nav>
            <a href=\"#\" id=\"nav-button\" class=\"right\">Menu</a>
        </div>

        <!-- Regular Nav -->
        <div class=\"nav-large hide-for-small-only\">
            <nav class=\"site-nav text-right row\">
                <a href=\"#\" id=\"nav-button\" class=\"right\">Menu</a>
                <ul>
                    ";
        // line 19
        $this->env->loadTemplate("parts/navigation.twig")->display($context);
        // line 20
        echo "                </ul>
            </nav>
        </div>

        <h1 class=\"site-title\">CodeDay <span class=\"current-city\">";
        // line 24
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["event"]) ? $context["event"] : null), "name"), "html", null, true);
        echo "</span><input type=\"text\" class=\"current-city\" value=\"\"></h1>
        <div id=\"city-picker\" class=\"round-corners text-left\">
            <h3 class=\"city-panel-title\">Nearest events</h3>
            <ul>
            </ul>
        </div>
        <p class=\"site-description\">Build something awesome in 24 hours!</p>
        <h2 class=\"event-date\">November 11-12th 2014</h2>
        ";
        // line 32
        if ($this->getAttribute((isset($context["event"]) ? $context["event"] : null), "venue")) {
            // line 33
            echo "            <h2 class=\"event-host\">Hosted at ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["event"]) ? $context["event"] : null), "venue"), "name"), "html", null, true);
            echo "</h2>
        ";
        }
        // line 35
        echo "        ";
        // line 36
        echo "        <div class=\"row text-center\">
\t        <input type=\"text\" id=\"subscription-email\" placeholder=\"Email\"><!--
\t        --><input type=\"submit\" value=\"Subscribe\" id=\"subscription-submit\">
        </div>
        <p class=\"show-for-large-up\"><a href=\"#\" id=\"video-link\">Watch video</a></p>
        <p class=\"show-for-medium-down\"><a href=\"http://vimeo.com/77693957\" target=\"_blank\" id=\"video-link\">Watch video</a></p>
    </div>

    <a href=\"#faqAnchor\" class=\"anchorLink\"><div id=\"arrow\"></div></a>

    <div id=\"overlay\"></div>
</div>

<div class=\"hide-for-small-only\">
    <a href=\"#\" id=\"splash-return\" class=\"round-corners\">Return</a>
    <video id=\"splash-video\">
        <source src=\"/assets/mp4/splash.mp4\" type=\"video/mp4\" autostart=\"false\">
    </video>
</div>

<div id=\"splash-bg\" class=\"hide-for-medium-up\"></div>
";
    }

    public function getTemplateName()
    {
        return "parts/index-splash.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  71 => 36,  69 => 35,  63 => 33,  61 => 32,  50 => 24,  44 => 20,  42 => 19,  30 => 9,  28 => 8,  19 => 1,);
    }
}
