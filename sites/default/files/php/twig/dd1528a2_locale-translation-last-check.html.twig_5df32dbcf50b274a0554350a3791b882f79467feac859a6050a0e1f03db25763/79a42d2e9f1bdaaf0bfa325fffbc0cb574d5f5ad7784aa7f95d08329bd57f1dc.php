<?php

/* core/themes/stable/templates/admin/locale-translation-last-check.html.twig */
class __TwigTemplate_6853dc550a2c97586c6ec0140fd78a4a4b1af43d91d7097c309f5c7d4c47f5d9 extends Twig_Template
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
        $tags = array("if" => 17, "trans" => 18);
        $filters = array("t" => 20);
        $functions = array();

        try {
            $this->env->getExtension('sandbox')->checkSecurity(
                array('if', 'trans'),
                array('t'),
                array()
            );
        } catch (Twig_Sandbox_SecurityError $e) {
            $e->setTemplateFile($this->getTemplateName());

            if ($e instanceof Twig_Sandbox_SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof Twig_Sandbox_SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof Twig_Sandbox_SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

        // line 15
        echo "<div class=\"locale checked\">
  <p>
  ";
        // line 17
        if ((isset($context["last_checked"]) ? $context["last_checked"] : null)) {
            // line 18
            echo "    ";
            echo t("Last checked: @time ago", array("@time" => (isset($context["time"]) ? $context["time"] : null), ));
            // line 19
            echo "  ";
        } else {
            // line 20
            echo "    ";
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar(t("Last checked: never")));
            echo "
  ";
        }
        // line 22
        echo "  <span class=\"check-manually\">(";
        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["link"]) ? $context["link"] : null), "html", null, true));
        echo ")</span></p>
</div>
";
    }

    public function getTemplateName()
    {
        return "core/themes/stable/templates/admin/locale-translation-last-check.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  61 => 22,  55 => 20,  52 => 19,  49 => 18,  47 => 17,  43 => 15,);
    }
}
/* {#*/
/* /***/
/*  * @file*/
/*  * Theme override for the last time we checked for update data.*/
/*  **/
/*  * Available variables:*/
/*  * - last_checked: Whether or not locale updates have been checked before.*/
/*  * - time: The formatted time ago when the site last checked for available*/
/*  *   updates.*/
/*  * - link: A link to manually check available updates.*/
/*  **/
/*  * @see template_preprocess_locale_translation_last_check()*/
/*  *//* */
/* #}*/
/* <div class="locale checked">*/
/*   <p>*/
/*   {% if last_checked %}*/
/*     {% trans %} Last checked: {{ time }} ago {% endtrans %}*/
/*   {% else %}*/
/*     {{ 'Last checked: never'|t }}*/
/*   {% endif %}*/
/*   <span class="check-manually">({{ link }})</span></p>*/
/* </div>*/
/* */
