<?php

/* core/themes/stable/templates/admin/locale-translation-update-info.html.twig */
class __TwigTemplate_52be65a0d525ad133811c9ef5087d1ed07c541b0dcb55a9a6eb1a0717b10f07f extends Twig_Template
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
        $tags = array("if" => 18, "set" => 19, "trans" => 20, "for" => 34);
        $filters = array("safe_join" => 19, "length" => 25, "format_date" => 35, "t" => 45, "default" => 50);
        $functions = array();

        try {
            $this->env->getExtension('sandbox')->checkSecurity(
                array('if', 'set', 'trans', 'for'),
                array('safe_join', 'length', 'format_date', 't', 'default'),
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

        // line 16
        echo "<div class=\"locale-translation-update__wrapper\" tabindex=\"0\" role=\"button\">
  <span class=\"locale-translation-update__prefix visually-hidden\">Show description</span>
  ";
        // line 18
        if ((isset($context["modules"]) ? $context["modules"] : null)) {
            // line 19
            echo "    ";
            $context["module_list"] = $this->env->getExtension('drupal_core')->safeJoin($this->env, (isset($context["modules"]) ? $context["modules"] : null), ", ");
            // line 20
            echo "    <span class=\"locale-translation-update__message\">";
            echo t("Updates for: @module_list", array("@module_list" => (isset($context["module_list"]) ? $context["module_list"] : null), ));
            echo "</span>
  ";
        } elseif (        // line 21
(isset($context["not_found"]) ? $context["not_found"] : null)) {
            // line 22
            echo "    <span class=\"locale-translation-update__message\">";
            // line 23
            echo \Drupal::translation()->formatPlural(abs(twig_length_filter($this->env,             // line 25
(isset($context["not_found"]) ? $context["not_found"] : null))), "Missing translations for one project", "Missing translations for @count projects", array());
            // line 28
            echo "</span>
  ";
        }
        // line 30
        echo "  ";
        if (((isset($context["updates"]) ? $context["updates"] : null) || (isset($context["not_found"]) ? $context["not_found"] : null))) {
            // line 31
            echo "    <div class=\"locale-translation-update__details\">
      ";
            // line 32
            if ((isset($context["updates"]) ? $context["updates"] : null)) {
                // line 33
                echo "        <ul>
          ";
                // line 34
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable((isset($context["updates"]) ? $context["updates"] : null));
                foreach ($context['_seq'] as $context["_key"] => $context["update"]) {
                    // line 35
                    echo "            <li>";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute($context["update"], "name", array()), "html", null, true));
                    echo " (";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, call_user_func_array($this->env->getFilter('format_date')->getCallable(), array($this->getAttribute($context["update"], "timestamp", array()), "html_date")), "html", null, true));
                    echo ")</li>
          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['update'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 37
                echo "        </ul>
      ";
            }
            // line 39
            echo "      ";
            if ((isset($context["not_found"]) ? $context["not_found"] : null)) {
                // line 40
                echo "        ";
                // line 44
                echo "        ";
                if ((isset($context["updates"]) ? $context["updates"] : null)) {
                    // line 45
                    echo "          ";
                    echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->renderVar(t("Missing translations for:")));
                    echo "
        ";
                }
                // line 47
                echo "        ";
                if ((isset($context["not_found"]) ? $context["not_found"] : null)) {
                    // line 48
                    echo "          <ul>
            ";
                    // line 49
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable((isset($context["not_found"]) ? $context["not_found"] : null));
                    foreach ($context['_seq'] as $context["_key"] => $context["update"]) {
                        // line 50
                        echo "              <li>";
                        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute($context["update"], "name", array()), "html", null, true));
                        echo " (";
                        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (($this->getAttribute($context["update"], "version", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute($context["update"], "version", array()), t("no version"))) : (t("no version"))), "html", null, true));
                        echo "). ";
                        echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute($context["update"], "info", array()), "html", null, true));
                        echo "</li>
            ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['update'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 52
                    echo "          </ul>
        ";
                }
                // line 54
                echo "      ";
            }
            // line 55
            echo "    </div>
  ";
        }
        // line 57
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "core/themes/stable/templates/admin/locale-translation-update-info.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  146 => 57,  142 => 55,  139 => 54,  135 => 52,  122 => 50,  118 => 49,  115 => 48,  112 => 47,  106 => 45,  103 => 44,  101 => 40,  98 => 39,  94 => 37,  83 => 35,  79 => 34,  76 => 33,  74 => 32,  71 => 31,  68 => 30,  64 => 28,  62 => 25,  61 => 23,  59 => 22,  57 => 21,  52 => 20,  49 => 19,  47 => 18,  43 => 16,);
    }
}
/* {#*/
/* /***/
/*  * @file*/
/*  * Theme override for displaying translation status information.*/
/*  **/
/*  * Displays translation status information per language.*/
/*  **/
/*  * Available variables:*/
/*  * - modules: A list of modules names that have available translation updates.*/
/*  * - updates: A list of available translation updates.*/
/*  * - not_found: A list of modules missing translation updates.*/
/*  **/
/*  * @see template_preprocess_locale_translation_update_info()*/
/*  *//* */
/* #}*/
/* <div class="locale-translation-update__wrapper" tabindex="0" role="button">*/
/*   <span class="locale-translation-update__prefix visually-hidden">Show description</span>*/
/*   {% if modules %}*/
/*     {% set module_list = modules|safe_join(', ') %}*/
/*     <span class="locale-translation-update__message">{% trans %}Updates for: {{ module_list }}{% endtrans %}</span>*/
/*   {% elseif not_found %}*/
/*     <span class="locale-translation-update__message">*/
/*       {%- trans -%}*/
/*         Missing translations for one project*/
/*       {%- plural not_found|length -%}*/
/*         Missing translations for @count projects*/
/*       {%- endtrans -%}*/
/*     </span>*/
/*   {% endif %}*/
/*   {% if updates or not_found %}*/
/*     <div class="locale-translation-update__details">*/
/*       {% if updates %}*/
/*         <ul>*/
/*           {% for update in updates %}*/
/*             <li>{{ update.name }} ({{ update.timestamp|format_date('html_date') }})</li>*/
/*           {% endfor %}*/
/*         </ul>*/
/*       {% endif %}*/
/*       {% if not_found %}*/
/*         {#*/
/*           Prefix the missing updates list if there is an available updates lists*/
/*           before it.*/
/*         #}*/
/*         {% if updates %}*/
/*           {{ 'Missing translations for:'|t }}*/
/*         {% endif %}*/
/*         {% if not_found %}*/
/*           <ul>*/
/*             {% for update in not_found %}*/
/*               <li>{{ update.name }} ({{ update.version|default('no version'|t) }}). {{ update.info }}</li>*/
/*             {% endfor %}*/
/*           </ul>*/
/*         {% endif %}*/
/*       {% endif %}*/
/*     </div>*/
/*   {% endif %}*/
/* </div>*/
/* */
