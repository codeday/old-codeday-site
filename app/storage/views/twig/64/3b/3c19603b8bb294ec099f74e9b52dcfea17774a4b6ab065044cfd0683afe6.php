<?php

/* /var/www/new.codeday.org/app/config/../views/register.twig */
class __TwigTemplate_643b3c19603b8bb294ec099f74e9b52dcfea17774a4b6ab065044cfd0683afe6 extends TwigBridge\Twig\Template
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
        echo "Register";
    }

    // line 3
    public function block_page($context, array $blocks = array())
    {
        echo "register";
    }

    // line 4
    public function block_content($context, array $blocks = array())
    {
        // line 5
        echo "    <main class=\"row wrapper-column\">

        <div class=\"columns medium-6\">

            <div class=\"register-box round-corners\">
                <h2>CodeDay ";
        // line 10
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["event"]) ? $context["event"] : null), "name"), "html", null, true);
        echo "</h2>
                <iframe class=\"register-map\" src=\"https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d5377.2521925759165!2d-122.326322!3d47.633399!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x54901521431b5d01%3A0xdfaa192e0856f09e!2s1551+Eastlake+Ave+E%2C+Seattle%2C+WA+98102!5e0!3m2!1sen!2sus!4v1405657973519\" width=\"100%\" height=\"250\" frameborder=\"0\" style=\"border:0\"></iframe>
                <p class=\"register-host\">";
        // line 12
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["event"]) ? $context["event"] : null), "venue"), "name"), "html", null, true);
        echo "</p>
                <p class=\"register-address\">";
        // line 13
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["event"]) ? $context["event"] : null), "venue"), "full_address"), "html", null, true);
        echo "</p>
                <p class=\"register-date\">November 11-12, 2014</p>
            </div>

            <div class=\"register-box round-corners\">
                <h2>Purchase Info</h2>
                <span class=\"ticket-type\">Student</span><!--
\t\t\t\t--><span class=\"ticket-price\">\$10 (Early Bird 50% Off)</span><!--
\t\t\t\t--><span class=\"dropdown\">
\t\t\t\t<select id=\"ticket-amount\">
                    <option value=\"1\">1</option>
                    <option value=\"2\">2</option>
                    <option value=\"3\">3</option>
                    <option value=\"4\">4</option>
                    <option value=\"5\">5</option>
                    <option value=\"6\">6</option>
                    <option value=\"7\">7</option>
                    <option value=\"8\">8</option>
                    <option value=\"9\">9</option>
                    <option value=\"10\">10</option>
                </select></span>
                <div class=\"text-right\">
                    <span id=\"text-total\" class=\"text-center\">Total</span>
                    <span id=\"total-cost\" class=\"text-center\">\$10</span>
                </div>
                <input type=\"text\" id=\"promo-code\" name=\"promo-code\" placeholder=\"Enter Promotional Code\"><!--
\t\t\t\t--><a href=\"\" id=\"promo-code-button\" class=\"text-center\">Apply</a>
            </div>

        </div>

        <div class=\"columns medium-6\">
            <div id=\"coder-profile-1\" class=\"register-box round-corners coder-profile\">
                <form action=\"\" method=\"post\" autocomplete=\"off\">
                    <h2 class=\"coder-profile-title\">Attendee</h2>
                    <input type=\"text\" class=\"first-name\" name=\"first-name\" placeholder=\"First Name\"><!--
\t\t\t\t\t--><input type=\"text\" class=\"last-name\" name=\"last-name\" placeholder=\"Last Name\"><!--
\t\t\t\t\t--><input type=\"text\" class=\"age\" name=\"age\" placeholder=\"Age\" maxlength=\"2\"><!--
\t\t\t\t\t--><input type=\"text\" class=\"email\" name=\"email\" placeholder=\"Email\"><!--
\t\t\t\t\t--><input type=\"text\" class=\"confirm-email\" name=\"confirm-email\" placeholder=\"Confirm Email\">
                    <input type=\"checkbox\" id=\"subscribe1\" class=\"register-checkbox\" name=\"subscribe1\" value=\"subscribe\" checked><label for=\"subscribe1\">I’d like to recieve info from sponsors</label>
                </form>
            </div>
            <div class=\"register-box round-corners\">
                <form id=\"billing-info\" action=\"\" method=\"post\" autocomplete=\"off\">
                    <h2>Billing</h2>
                    <input type=\"text\" id=\"credit-num\" name=\"credit-num\" placeholder=\"Credit Card Number\"><!--
\t\t\t\t\t--><input type=\"text\" id=\"csc\" name=\"csc\" placeholder=\"CSC\" maxlength=\"3\"><!--
\t\t\t\t\t--><span id=\"expir-date\">Expiration Date</span><!--
\t\t\t\t\t--><input type=\"text\" id=\"mm\" name=\"mm\" placeholder=\"mm\" maxlength=\"2\"><!--
\t\t\t\t\t--><span> /</span><input type=\"text\" id=\"yy\" name=\"yy\" placeholder=\"yy\" maxlength=\"2\">
                </form>
            </div>

            <a href=\"\" id=\"pay-button\" class=\"round-corners text-center\">Pay Now</a>

            <p id=\"toa\">By clicking “Pay Now”, I have read and agree with the <a href=\"\">CodeDay terms of service</a>, <a href=\"\">CodeDay code of conduct</a>, and possibly getting my picture taken during the event.</p>

        </div>

    </main>
";
    }

    public function getTemplateName()
    {
        return "/var/www/new.codeday.org/app/config/../views/register.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  61 => 13,  57 => 12,  52 => 10,  45 => 5,  42 => 4,  36 => 3,  30 => 2,);
    }
}
