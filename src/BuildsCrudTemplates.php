<?php

namespace Evercode1\ViewMaker;

trait BuildsCrudTemplates
{

    private $crudTemplate;

    private function getContentFromTemplate($fileName,  array $tokens, $fileExists=false)
    {

        switch($fileName){

            case 'model' :

                if ($this->hasParent($tokens)){

                    if ($this->isParent($tokens)){

                        return $this->buildTemplate($tokens, 'parentModelTemplate');
                        break;

                    } else {

                        if ($this->isChild($tokens)) {

                            return $this->buildTemplate($tokens, 'childModelTemplate');
                            break;
                        }

                    }


                }

                return $this->buildTemplate($tokens, 'modelTemplate');
                break;

            case 'controller' :

                if ($this->hasChild($tokens)){

                        if ($this->isChild($tokens)) {

                            return $this->buildTemplate($tokens, 'childControllerTemplate');
                            break;
                        }


                }

                return $this->buildTemplate($tokens, 'controllerTemplate');
                break;

            case 'apiController' :

                if ($fileExists){

                    if ($this->hasChild($tokens)){

                        if ($this->isChild($tokens)) {

                            return $this->buildTemplate($tokens, 'childAppendApiControllerTemplate');
                            break;
                        }


                    }

                    return $this->buildTemplate($tokens, 'appendApiControllerTemplate');
                    break;

                } else {

                    if ($this->hasChild($tokens)){

                        if ($this->isChild($tokens)) {

                            return $this->buildTemplate($tokens, 'childApiControllerTemplate');
                            break;
                        }


                    }

                    return $this->buildTemplate($tokens, 'apiControllerTemplate');
                    break;

                }


            case 'migration' :

                if ($this->hasChild($tokens)){

                    if ($this->isChild($tokens)) {

                        return $this->buildTemplate($tokens, 'childMigrationTemplate');
                        break;
                    }


                }

                return $this->buildTemplate($tokens, 'migrationTemplate');
                break;

            case 'routes' :
                return $this->buildTemplate($tokens, 'routeTemplate');
                break;

            case 'factory' :

                if ($this->hasChild($tokens)){

                    if ($this->isChild($tokens)) {

                        return $this->buildTemplate($tokens, 'childFactoryTemplate');
                        break;
                    }


                }
                return $this->buildTemplate($tokens, 'factoryTemplate');
                break;

            case 'test' :

                if ($this->hasChild($tokens)){

                    if ($this->isChild($tokens)) {

                        return $this->buildTemplate($tokens, 'childTestTemplate');
                        break;
                    }


                }
                return $this->buildTemplate($tokens, 'testTemplate');
                break;


            default :

                return 'Something went wrong';



        }

    }

    private function buildTemplate($tokens, $template)
    {

        $this->setTemplateInstance($tokens);

        return $this->crudTemplate->$template();


    }

    private function setTemplateInstance($tokens)
    {
        $this->crudTemplate = new CrudTemplates($tokens);

    }

}