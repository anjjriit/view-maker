<?php

namespace Evercode1\ViewMaker;

trait BuildsChartTemplates
{

    private $chartTemplate;

    private function getContentFromTemplate($fileName,  array $tokens)
    {

        switch($fileName){


            case 'ApiController' :

                return $this->buildTemplate($tokens, 'AppendChartToApiControllerTemplate');
                break;

            case 'Route' :

                return $this->buildTemplate($tokens, 'AppendChartRouteTemplate');
                break;

            case 'ChartCss' :

                return $this->buildTemplate($tokens, 'AppendChartCssTemplate');
                break;

            case 'ChartTemplate' :

                return $this->buildTemplate($tokens, 'AppendChartTemplate');
                break;

            case 'ChartScript' :

                return $this->buildTemplate($tokens, 'AppendChartScriptTemplate');
                break;

            case 'ChartCdn' :

                return $this->buildTemplate($tokens, 'AppendChartCdnTemplate');
                break;

            default :

                return 'Something went wrong';



        }

    }

    private function buildTemplate($tokens, $template)
    {

        $this->setTemplateInstance($tokens);

        return $this->chartTemplate->$template();


    }

    private function setTemplateInstance($tokens)
    {
        $this->chartTemplate = new ChartTemplates($tokens);

    }

}