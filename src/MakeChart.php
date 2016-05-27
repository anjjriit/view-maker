<?php

namespace Evercode1\ViewMaker;

use Illuminate\Console\Command;

class MakeChart extends Command
{
    use MatchesPatterns, BuildsChartTemplates;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:chart
                           {ModelName}';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'create chart based on model';


    protected $files = [];

    private $model;

    private $modelPath;

    private $tableName;

    public $tokens = [];


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->setConfig();

        $this->checkRequirements();

        if ( $this->InsertChartIntoFiles() ) {

            $this->sendSuccessMessage();

            return;

        }

        $this->error('Oops, something went wrong!');


    }

    private function sendSuccessMessage()
    {

        $this->info('Chart successfully created');

    }

    private function setConfig()
    {
        $this->model = $this->argument('ModelName');
        $this->modelPath = $this->formatModelPath($this->model);
        $this->tableName = $this->formatTableName($this->model);
        $this->files['Index'] = base_path('resources/views/' . $this->modelPath . '/index.blade.php');
        $this->files['Route'] = base_path('app/Http/routes.php');
        $this->files['ApiController'] = base_path('app/Http/Controllers/ApiController.php');
        $this->tokens['model'] = $this->model;

    }

    private function formatModelName($model)
    {
        return $model = ucwords($model);

    }

    private function formatModelPath($model)
    {
        $model = preg_split('/(?=[A-Z])/',$model);

        $model = implode('-', $model);

        $model = ltrim($model, '-');

        return $model = strtolower($model);

    }

    private function formatTableName($model)
    {

        $model = $this->formatModelName($model);

        $model = snake_case($model);

        $model = strtolower($model);

        return $model = str_plural($model);


    }

    private function checkRequirements()
    {

        foreach($this->files as $name => $path){

            if( ! file_exists($path)){

                $this->error('Oops, you are missing required files! Typo in the model name?');

                die();
            }

            if ( $str = file_get_contents($this->files['Index'])){

                $pattern = '/Begin Vue CDN Call/';

               if( ! $this->patternMatchView($pattern, $str)) {

                   $this->error('
                                 Sorry, you don\'t have the vue.js dependency in your index file.
                                 Our make:chart command only works with vue templates.
                                 If you are using vue templates, then it\'s because you don\'t have
                                 the CDN call commented the way we need it in the file. Run a new make views
                                 command with vue template with the latest version of ViewMaker to see
                                 how it should be formatted.');

                   die();
                }

            }

        }

            return true;


    }

    private function InsertChartIntoFiles()
    {

        $this->writeEachFile();

        return $this;

    }

    private function writeEachFile()
    {
        $this->appendRoute();
        $this->appendChartApiControllerMethod();
        $this->appendCssToIndex();
        $this->appendChartTemplateToIndex();
        $this->appendChartVueScript();
        $this->appendChartCdnCall();
    }

    private function appendChartCdnCall()
    {
        $txt = $this->getContentFromTemplate('ChartCdn', $this->tokens);

        $contents = file_get_contents($this->files['Index']);

        $classParts = explode("<!-- End Grid Requirement -->", $contents, 2);

        $txt = $classParts[0] . "<!-- End Grid Requirement -->" . "\n\n" . $txt . $classParts[1];

        $handle = fopen($this->files['Index'], "w");

        fwrite($handle, $txt);

        fclose($handle);

        return $this;



    }
    private function appendCssToIndex()
    {
            $txt = $this->getContentFromTemplate('ChartCss', $this->tokens);

            $contents = file_get_contents($this->files['Index']);

            $classParts = explode("@section('css')", $contents, 2);

            $txt = $classParts[0] . "@section('css')" . "\n\n" . $txt . $classParts[1];

            $handle = fopen($this->files['Index'], "w");

            fwrite($handle, $txt);

            fclose($handle);

            return $this;


    }



    private function appendChartVueScript()
    {

        $txt = $this->getContentFromTemplate('ChartScript', $this->tokens);

        $contents = file_get_contents($this->files['Index']);

        $classParts = explode("<!-- Chart Script Placeholder -->", $contents, 2);

        $txt = $classParts[0] . "<!-- Begin Chart Script -->" . "\n\n" . $txt . $classParts[1];

        $handle = fopen($this->files['Index'], "w");
        fwrite($handle, $txt);

        fclose($handle);

        return $this;


    }

    private function appendChartTemplateToIndex()
    {

        $txt = $this->getContentFromTemplate('ChartTemplate', $this->tokens);

        $contents = file_get_contents($this->files['Index']);

        $classParts = explode("</ol>", $contents, 2);

        $txt = $classParts[0] . "</ol>" . "\n\n" . $txt . $classParts[1];

        $handle = fopen($this->files['Index'], "w");
        fwrite($handle, $txt);

        fclose($handle);

        return $this;


    }

    private function appendRoute()
    {
        $txt = $this->getContentFromTemplate('Route', $this->tokens);

        $handle = fopen($this->files['Route'], "a");

        fwrite($handle, $txt);

        fclose($handle);

    }

    private function appendChartApiControllerMethod()
    {

        $txt = $this->getContentFromTemplate('ApiController', $this->tokens);

        $contents = file_get_contents($this->files['ApiController']);

        $classParts = explode('{', $contents, 2);

        $txt = $classParts[0]. "{\n\n" . $txt . "\n\n"  . $classParts[1];

        $handle = fopen($this->files['ApiController'], "w");

        fwrite($handle, $txt);

        fclose($handle);



    }




}
