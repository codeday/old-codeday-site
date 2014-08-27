<?php

/* template.twig */
class __TwigTemplate_7922164aeed19b1c41c549411c33bf3784cecbc73035803f73474770842d261b extends TwigBridge\Twig\Template
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
        echo "<!DOCTYPE html>
<!--[if lt IE 7]>      <html class=\"no-js lt-ie9 lt-ie8 lt-ie7\"> <![endif]-->
<!--[if IE 7]>         <html class=\"no-js lt-ie9 lt-ie8\"> <![endif]-->
<!--[if IE 8]>         <html class=\"no-js lt-ie9\"> <![endif]-->
<!--[if gt IE 8]><!--> <html class=\"no-js\"> <!--<![endif]-->
<head>
    <meta charset=\"utf-8\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
    <title>
        ";
        // line 10
        if ($this->renderBlock("title", $context, $blocks)) {
            // line 11
            echo "            ";
            $this->displayBlock("title", $context, $blocks);
            echo " |
        ";
        }
        // line 13
        echo "        CodeDay
    </title>
    <meta name=\"description\" content=\"CodeDay Spring 2014. May 24th-25th, noon-noon. Build something awesome in 24 hours! share tweet. Join 2,000+ students at CodeDay in a city near you!\">
    <meta name=\"viewport\" content=\"width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1\">

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <link rel=\"stylesheet\" href=\"http://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.1/normalize.min.css\">
    <link rel=\"stylesheet\" href=\"http://cdnjs.cloudflare.com/ajax/libs/foundation/5.3.1/css/foundation.min.css\">
    <link rel=\"stylesheet\" href=\"/assets/css/main.css\">
    <script src=\"http://www.geoplugin.net/javascript.gp\" type=\"text/javascript\"></script>
</head>
<body>

";
        // line 26
        if ($this->renderBlock("splash", $context, $blocks)) {
            // line 27
            echo "    ";
            $this->displayBlock("splash", $context, $blocks);
            echo "
";
        }
        // line 29
        echo "
";
        // line 30
        if ($this->renderBlock("splash", $context, $blocks)) {
            // line 31
            echo "    <div id=\"over\">
";
        } else {
            // line 33
            echo "    <div id=\"";
            $this->displayBlock("page", $context, $blocks);
            echo "-page\" class=\"subpage\">
        ";
            // line 35
            echo "        <header class=\"header-subpage header-mobile hide-for-medium-up\">
            <nav class=\"site-nav text-right\">
                <ul>
                    ";
            // line 38
            $this->env->loadTemplate("parts/navigation.twig")->display($context);
            // line 39
            echo "                </ul>
            </nav>
            <div class=\"row\">
                <h1 class=\"site-title left\"><a href=\"/\">CodeDay</a></h1>
                <a href=\"#\" id=\"nav-button\" class=\"right\">Menu</a>
            </div>
            <h1 class=\"subpage-title text-center\">Sponsor</h1>
        </header>

        ";
            // line 49
            echo "        <header class=\"header-subpage header-large hide-for-small-only\">
            <div class=\"row\">
                <div class=\"column large-3 small-3\">
                    <h1 class=\"site-title\"><a href=\"/\">CodeDay</a></h1>
                </div>
                <div class=\"column large-9 small-9\">
                    <nav class=\"site-nav text-right\">
                        <a href=\"#\" id=\"nav-button\" class=\"right\">Menu</a>
                        <ul>
                            ";
            // line 58
            $this->env->loadTemplate("parts/navigation.twig")->display($context);
            // line 59
            echo "                        </ul>
                    </nav>
                </div>
            </div>
            <h1 class=\"subpage-title text-center\">";
            // line 63
            $this->displayBlock("title", $context, $blocks);
            echo "</h1>
        </header>
";
        }
        // line 66
        echo "
    ";
        // line 67
        $this->displayBlock("content", $context, $blocks);
        echo "

    <footer id=\"site-footer\">
        <div class=\"row\">
            <div class=\"columns medium-5 text-left\">
                <p>Organized in Seattle by <a href=\"https://studentrnd.org/\" class=\"studentrnd\">StudentRND</a>.</p>
                <p class=\"copyright\">&copy; <?php echo date(\"Y\") ?> CodeDay. All rights reserved.</p>
            </div>
            <div class=\"columns medium-7 hide-for-small-only\">
                <nav class=\"site-nav text-right row\">
                    <ul>
                        <li><a href=\"/rules\">Rules</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </footer>
</div>


<!-- <script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js\"></script>
<script>window.jQuery || document.write('<script src=\"js/vendor/jquery-1.10.2.min.js\"><\\/script>')</script>
<script src=\"js/plugins.js\"></script>-->
<script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js\"></script>
<script src=\"http://masonry.desandro.com/masonry.pkgd.min.js\"></script>
<script>
    window.lat = ";
        // line 93
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["visitor"]) ? $context["visitor"] : null), "lat"), "html", null, true);
        echo ";
    window.lng = ";
        // line 94
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["visitor"]) ? $context["visitor"] : null), "lng"), "html", null, true);
        echo ";
</script>
<script src=\"/assets/js/main.js\"></script>

</body>
</html>";
    }

    public function getTemplateName()
    {
        return "template.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  154 => 94,  150 => 93,  121 => 67,  118 => 66,  112 => 63,  106 => 59,  104 => 58,  93 => 49,  82 => 39,  80 => 38,  75 => 35,  70 => 33,  66 => 31,  64 => 30,  61 => 29,  55 => 27,  53 => 26,  38 => 13,  32 => 11,  30 => 10,  19 => 1,);
    }
}
