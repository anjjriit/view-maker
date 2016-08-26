<?php

namespace Evercode1\ViewMaker;

use Illuminate\Console\Command;

class RemoveChart extends Command
{
    use FormatsInput, RemovesFiles;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:chart
                           {ModelName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'remove chart from files';
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

        $this->modelName = $this->formatModel($this->argument('ModelName'));

        $this->modelPath = $this->formatModelPath($this->argument('ModelName'));

        if ( ! $this->chartExists()){

            $this->error('Error, can\'t find ' .$this->modelName . ' chart');
            die();

        }


        if ( $this->removeChartFromFiles() ) {

            $this->sendSuccessMessage();

            return;

        }

        $this->error('Oops, something went wrong!');


    }

    private function sendSuccessMessage()
    {

        $this->info($this->modelName . ' Chart successfully removed');

    }

    private function chartExists()
    {
        $start = '<!-- Begin ' . $this->modelName . ' Chart -->';

        $end = '<!-- End ' . $this->modelName . ' Chart -->';

        $file = base_path('resources/views/' . $this->modelPath . '/index.blade.php');

        //read the entire string from file

        $content = file_get_contents($file);

        // do we have the chart?

        return $this->patternMatch($start, $end, $content) ? true : false;


    }

    private function formatModel($model)
    {
        $model = camel_case($model);
        $model = str_singular($model);
        
        return $model = ucwords($model);

    }

    private function removeChartFromFiles()
    {
        $this->removeApiControllerMethod();
        $this->removeChartRoute();
        $this->removeChartCssFromIndex();
        $this->removeChartTemplateFromIndex();
        $this->removeChartCdnCallFromIndex();
        $this->removeChartScriptFromIndex();

        return $this;

    }

    private function removeApiControllerMethod()
    {

        $start = '// Begin ' . $this->modelName . ' Chart Api Method';

        $end = '// End ' . $this->modelName . ' Chart Api Method';

        $replaceWith = "";

        $file = base_path('app/Http/Controllers/ApiController.php');

        //read the entire string from file

        $content = file_get_contents($file);

        // define pattern

        $stringToDelete = $this->patternMatch($start, $end, $content);

        //replace the file string

        $updatedContent = str_replace("$stringToDelete", "$replaceWith", $content);

        //writes the entire file with updated content

        file_put_contents($file, $updatedContent);


    }

    private function removeChartRoute()
    {

        $start = '// Begin ' . $this->modelName . ' Chart Route';

        $end = '// End ' . $this->modelName . ' Chart Route';

        $replaceWith = "";

        $file = base_path('app/Http/routes.php');

        //read the entire string from file

        $content = file_get_contents($file);

        // define pattern

        $stringToDelete = $this->patternMatch($start, $end, $content);

        //replace the file string

        $updatedContent = str_replace("$stringToDelete", "$replaceWith", $content);

        //writes the entire file with updated content

        file_put_contents($file, $updatedContent);

    }

    private function removeChartCssFromIndex()
    {

        $start = '<!-- Begin Css For Chart -->';

        $end = '<!-- End Css For Chart -->';

        $replaceWith = "";

        $file = base_path('resources/views/' . $this->modelPath . '/index.blade.php');

        //read the entire string from file

        $content = file_get_contents($file);

        // define pattern

        $stringToDelete = $this->patternMatch($start, $end, $content);

        //replace the file string

        $updatedContent = str_replace("$stringToDelete", "$replaceWith", $content);

        //writes the entire file with updated content

        file_put_contents($file, $updatedContent);

    }

    private function removeChartTemplateFromIndex()
    {

        $start = '<!-- Begin ' . $this->modelName . ' Chart -->';

        $end = '<!-- End ' . $this->modelName . ' Chart -->';

        $replaceWith = "";

        $file = base_path('resources/views/' . $this->modelPath . '/index.blade.php');

        //read the entire string from file

        $content = file_get_contents($file);

        // define pattern

        $stringToDelete = $this->patternMatch($start, $end, $content);

        //replace the file string

        $updatedContent = str_replace("$stringToDelete", "$replaceWith", $content);

        //writes the entire file with updated content

        file_put_contents($file, $updatedContent);


    }

    private function removeChartCdnCallFromIndex()
    {

        $start = '<!-- Begin Chart.js CDN Call-->';

        $end = '<!-- End Chart.js CDN Call-->';

        $replaceWith = "";

        $file = base_path('resources/views/' . $this->modelPath . '/index.blade.php');

        //read the entire string from file

        $content = file_get_contents($file);

        // define pattern

        $stringToDelete = $this->patternMatch($start, $end, $content);

        //replace the file string

        $updatedContent = str_replace("$stringToDelete", "$replaceWith", $content);

        //writes the entire file with updated content

        file_put_contents($file, $updatedContent);


    }

    private function removeChartScriptFromIndex()
    {
        $start = '<!-- Begin Chart Script -->';

        $end = '<!-- End Chart Script -->';

        $replaceWith = '<!-- Chart Script Placeholder -->';

        $file = base_path('resources/views/' . $this->modelPath . '/index.blade.php');

        //read the entire string from file

        $content = file_get_contents($file);

        // define pattern

        $stringToDelete = $this->patternMatch($start, $end, $content);

        //replace the file string

        $updatedContent = str_replace("$stringToDelete", "$replaceWith", $content);

        //writes the entire file with updated content

        file_put_contents($file, $updatedContent);


    }

}
