<?php

/* /var/www/new.codeday.org/app/config/../views/press.twig */
class __TwigTemplate_745837650176d5fcc189aeed9c5364545ec0bfa6727e1ee66311c95e5ff6cc18 extends TwigBridge\Twig\Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("template.twig");

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'page' => array($this, 'block_page'),
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
    public function block_title($context, array $blocks = array())
    {
        echo "Press";
    }

    // line 3
    public function block_page($context, array $blocks = array())
    {
        echo "press";
    }

    // line 4
    public function block_content($context, array $blocks = array())
    {
        // line 5
        echo "
        <main id=\"press-container\" class=\"content row round-corners\">
            <div class=\"column medium-8 wrapper-column\">
                <h2 class=\"section-title\">Who We Are</h2>
                <hr class=\"separator\">
                <p>CodeDay is a series of student programming events held across the world. It's the world's largest series of educational programming marathons, in addition to being one of the most effective methods of education.</p>

                <p>Our participants enter with a wide variety of skill levels, but they leave as some of the most skilled in the nation. Past participants have gone on to create venture-funded startups, gain hundreds of thousands of downloads on app stores, and more.</p>

                <h2 class=\"section-title\">Past Coverage</h2>
                <hr class=\"separator\">
                <article class=\"press-article\">
                    <span>The Gazette, 02/2014</span>
                    <h3><a href=\"http://thegazette.com/2014/02/16/a-coding-marathon/\">A coding marathon</a></h3>
                </article>
                <article class=\"press-article\">
                    <span>KCRG, 02/2014</span>
                    <h3><a href=\"http://www.kcrg.com/news/local/CodeDay-Comes-to-Eastern-Iowa-245715551.html\">CodeDay Comes to Eastern Iowa</a></h3>
                </article>
                <article class=\"press-article\">
                    <span>All Things Considered, 01/2014</span>
                    <h3><a href=\"http://www.npr.org/blogs/alltechconsidered/2014/01/25/266162832/computers-are-the-future-but-does-everyone-need-to-code\">Computers are the Future, But Does Everyone Need to Code?</a></h3>
                </article>
                <article class=\"press-article\">
                    <span>Silicon Prairie News, 01/2014</span>
                    <h3><a href=\"http://www.siliconprairienews.com/2014/01/cedar-rapids-kansas-city-bring-codeday-to-students-in-february\">Cedar Rapids, Kansas City bring CodeDay to Students in Februrary</a></h3>
                </article>
                <article class=\"press-article\">
                    <span>Geekwire, 01/2014</span>
                    <h3><a href=\"http://www.geekwire.com/2014/black-girls-code-code-org-among-named-azuredev-grant-winners/\">Black Girls Code, Code.org win Microsoft AzureDev grants</a></h3>
                </article>
                <article class=\"press-article\">
                    <span>Corvallis Gazette-Times, 12/2014</span>
                    <h3><a href=\"http://www.gazettetimes.com/news/local/codeday-corvallis-set-for-jan/article_7a53f4b0-70cd-11e3-8ea8-001a4bcf887a.html\">CodeDay Corvallis set for Jan. 18</a></h3>
                </article>
                <div id=\"press-more-past-coverage\"></div>
                <span id=\"view-more\">View More</span>
            </div>
            <div class=\"column medium-4 wrapper-column\">
                <h2 class=\"section-title\">Media Relations</h2>
                <hr class=\"separator\">
                <ul class=\"press-contact-info\">
                    <li id=\"phone-number\" class=\"press-icon\">(866) 382-2377</li>
                    <li id=\"email\" class=\"press-icon\"><a href=\"mailto:press@codeday.org?subject=Press Inquiry\">press@codeday.org</a></li>
                </ul>

                <h2 class=\"section-title\">Fast Stats</h2>
                <hr class=\"separator\">
                <ul class=\"press-stats\">
                    <li id=\"stats-hours\" class=\"press-icon\"><span class=\"stats-numbers\" data-from=\"0\" data-to=\"3880\" data-speed=\"1000\" data-refresh-interval=\"25\">3880</span> hours coding</li>
                    <li id=\"stats-locations\" class=\"press-icon\"><span class=\"stats-numbers\">26</span> cities nationwide</li>
                    <li id=\"stats-projects\" class=\"press-icon\"></span><span class=\"stats-numbers\">2,000+</span> projects</li>
                    <li id=\"stats-participants\" class=\"press-icon\"></span><span class=\"stats-numbers\">10,000+</span> participants</li>
                </ul>
                <h2 class=\"section-title\">Press Kit</h2>
                <hr class=\"separator\">
                <p>Find logos, images of CodeDay in action, and other assets in our press kit.</p>
                <div class=\"press-kit text-center round-corners\"><a href=\"\"><span></span>Press Kit</a></div>
            </div>
        </main>
";
    }

    public function getTemplateName()
    {
        return "/var/www/new.codeday.org/app/config/../views/press.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  45 => 5,  42 => 4,  36 => 3,  30 => 2,);
    }
}
