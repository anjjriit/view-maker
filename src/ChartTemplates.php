<?php

namespace Evercode1\ViewMaker;


class ChartTemplates
{

    public $tokenBuilder;

    public function __construct(array $tokens)
    {

        $this->tokenBuilder = new CrudTokens($tokens);

    }

    public function AppendChartRouteTemplate()
    {

        $content = <<<EOD

// Begin :::model::: Chart Route

Route::get(':::chartApiRoute:::', 'ApiController@:::chartApiControllerMethod:::');

// End :::model::: Chart Route
EOD;

        return $this->tokenBuilder->insertTokensIntoContent($content);


    }

    public function AppendChartToApiControllerTemplate()
    {
        $content = <<<EOD
// Begin :::model::: Chart Api Method

    public function :::chartApiControllerMethod:::(Request \$request){

        if (\$request->has('period')){

            switch(\$request->get('period')){

                case '3months' :

                    // set first and last date

                    \$currentYear = \Carbon\Carbon::now()->toDateString();
                    \$lastYear = \Carbon\Carbon::parse('first day of -2 month')->toDateString();

                    \$rows = DB::table(':::tableName:::')->select(DB::raw('Year(created_at) as year'),
                        DB::raw('month(created_at) as month'),
                        DB::raw("count(:::tableName:::.id) as `count`"))
                        ->where(DB::raw('date(created_at)'), '>=', \$lastYear)
                        ->where(DB::raw('date(created_at)'), '<=', \$currentYear)
                        ->groupBy('year', 'month')
                        ->get();

                    // dynamically create range of month/value pairs using carbon

                    for (\$i = 0; \$i <= 2; \$i++) {
                        \$values[intval(\Carbon\Carbon::parse("\$lastYear + \$i month")->format('m'))] = 0;
                    }

                    \$months = [1 => 'Jan', 2 => 'Feb', 3 =>'Mar', 4 => 'Apr', 5 => 'May',
                               6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct',
                               11 => 'Nov', 12 => 'Dec'];

                    //replace keys in values where key in months matches key in values with value in months of matching key

                    \$newValues = [];

                    foreach(\$values as \$monthNumber => \$count){

                        \$key = \$months[\$monthNumber];
                        \$newValues[\$key] = \$count;


                    }

                    \$labels= array_keys(\$newValues);


                    foreach(\$rows as \$row){

                        //overwrite values into values array

                        \$values [\$row->month] = \$row->count;
                    }

                    \$values = array_values(\$values);

                    \$data['data'] = compact('labels', 'values');

                    return response()->json(\$data);

                    break;

                case '1week' :

                    // set first and last date

                    \$today = \Carbon\Carbon::now()->toDateString();
                    \$lastWeek = \Carbon\Carbon::parse('-6 days')->toDateString();

                    \$rows = DB::table(':::tableName:::')->select(DB::raw('day(created_at) as day'),
                        DB::raw('month(created_at) as month'),
                        DB::raw("count(:::tableName:::.id) as `count`"))
                        ->where(DB::raw('date(created_at)'), '>=', \$lastWeek)
                        ->where(DB::raw('date(created_at)'), '<=', \$today)
                        ->groupBy('month', 'day')
                        ->get();

                    // dynamically create range of month/day pairs using carbon

                    for (\$i = 0; \$i <= 6; \$i++) {
                        \$labels[intval(\Carbon\Carbon::parse("\$lastWeek + \$i day")->format('m')) . '/' .intval(\Carbon\Carbon::parse("\$lastWeek + \$i day")->format('d'))] = 0;
                    }

                    \$labels= array_keys(\$labels);

                    for (\$i = 0; \$i <= 6; \$i++) {
                        \$values[intval(\Carbon\Carbon::parse("\$lastWeek + \$i day")->format('d'))] = 0;
                    }

                    //assign each day counts to values


                    foreach(\$rows as \$row){

                        \$values [\$row->day] = \$row->count;
                    }


                    \$values = array_values(\$values);

                    \$data['data'] = compact('labels', 'values');

                    return response()->json(\$data);

                    break;

                case '30days' :

                    \$today = \Carbon\Carbon::now()->toDateString();
                    \$lastWeek = \Carbon\Carbon::parse('-29 days')->toDateString();

                    \$rows = DB::table(':::tableName:::')->select(DB::raw('day(created_at) as day'),
                        DB::raw('month(created_at) as month'),
                        DB::raw("count(:::tableName:::.id) as `count`"))
                        ->where(DB::raw('date(created_at)'), '>=', \$lastWeek)
                        ->where(DB::raw('date(created_at)'), '<=', \$today)
                        ->groupBy('month', 'day')
                        ->get();


                    // dynamically create range of month/day pairs using carbon

                    for (\$i = 0; \$i <= 29; \$i++) {
                        \$labels[intval(\Carbon\Carbon::parse("\$lastWeek + \$i day")->format('m')) . '/' .intval(\Carbon\Carbon::parse("\$lastWeek + \$i day")->format('d'))] = 0;
                    }

                    \$labels= array_keys(\$labels);

                    // build values array

                    for (\$i = 0; \$i <= 29; \$i++) {
                        \$values[intval(\Carbon\Carbon::parse("\$lastWeek + \$i day")->format('d'))] = 0;
                    }

                    //assign each day counts to values

                    foreach(\$rows as \$row){

                        \$values [\$row->day] = \$row->count;
                    }

                    \$values = array_values(\$values);

                    \$data['data'] = compact('labels', 'values');

                    return response()->json(\$data);

                    break;


            }


        }

        // set first and last date

        \$currentYear = \Carbon\Carbon::now()->toDateString();
        \$lastYear = \Carbon\Carbon::parse('first day of -11 month')->toDateString();

        \$rows = DB::table(':::tableName:::')->select(DB::raw('Year(created_at) as year'),
            DB::raw('month(created_at) as month'),
            DB::raw("count(:::tableName:::.id) as `count`"))
            ->where(DB::raw('date(created_at)'), '>=', \$lastYear)
            ->where(DB::raw('date(created_at)'), '<=', \$currentYear)
            ->groupBy('year', 'month')
            ->get();

        // dynamically create range of month/value pairs using carbon

        \$values[intval(\Carbon\Carbon::parse(\$lastYear)->format('m'))] = 0;

        for (\$i = 0; \$i <= 11; \$i++) {
            \$values[intval(\Carbon\Carbon::parse("\$lastYear + \$i month")->format('m'))] = 0;
        }

        \$months = [1 => 'Jan', 2 => 'Feb', 3 =>'Mar', 4 => 'Apr', 5 => 'May',
                   6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct',
                   11 => 'Nov', 12 => 'Dec'];

        //replace keys in values where key in months matches key in values with value in months of matching key

        \$newValues = [];

       foreach(\$values as \$monthNumber => \$count){

           \$key = \$months[\$monthNumber];
           \$newValues[\$key] = \$count;


       }

        \$labels= array_keys(\$newValues);


        foreach(\$rows as \$row){

            //overwrite values into values array

            \$values [\$row->month] = \$row->count;
        }

        \$values = array_values(\$values);


        \$currentYear = \Carbon\Carbon::parse(\$currentYear)->format('y');
        \$lastYear = \Carbon\Carbon::parse(\$lastYear)->format('y');


        \$data['data'] = compact('labels', 'values', 'currentYear', 'lastYear');

        return response()->json(\$data);



    }

    // End :::model::: Chart Api Method
EOD;

        return $this->tokenBuilder->insertTokensIntoContent($content);


    }

    public function AppendChartCdnTemplate()
    {

        $content = <<<EOD
    <!-- Begin Chart.js CDN Call-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.3/Chart.min.js"></script>
    <!-- End Chart.js CDN Call-->
EOD;

        return $content;

    }

    public function AppendChartCssTemplate()
    {
        $content = <<<EOD
    <!-- Begin Css For Chart -->
    <!--you should move chart style to a permanent home -->

    <style>
        canvas {
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
        }

        #chart {
            width: 600px;
            height: 400px;
            margin-bottom: 150px;

        }

        #label {

            margin-top: 20px;
        }
    </style>
    <!-- End Css For Chart -->
EOD;

        return $this->tokenBuilder->insertTokensIntoContent($content);

    }

    public function AppendChartScriptTemplate()
    {
        $content = <<<EOD
    <!--you should move this to a permanent home -->

    <script>

        var \$myChart;

        // register the graph component

        Vue.component('graph', {
            template: '#graph-template',

            data: function(){

                return {
                    labels: [],
                    values: [],
                    name: ':::model:::',
                    type: 'line',
                    period: '1year'
                };

            },

            ready: function () {

                this.loadData();

            },

            methods: {

                changeType: function () {

                    this.setConfig();

                },

                loadData: function () {

                    $.getJSON(':::chartApiRoute:::', function (data) {

                        this.labels = data.data.labels;
                        this.values = data.data.values;
                        this.setConfig(this.type);

                    }.bind(this));

                },

                changePeriod: function () {

                    $.getJSON(':::chartApiRoute:::?period=' + this.period, function (data) {

                        this.labels = data.data.labels;
                        this.values = data.data.values;
                        this.setConfig(this.type);

                    }.bind(this));

                },

                setConfig : function () {
                    var ctx = document.getElementById('canvass').getContext('2d');
                    var config = {
                        type: this.type,
                        data: {
                            labels: this.labels,
                            datasets: [{
                                label: this.name,
                                data: this.values,
                                fill: true,
                                borderDash: [5, 5]
                            }]
                        },
                        options: {
                            responsive: true,
                            legend: {
                                position: 'bottom'
                            },
                            hover: {
                                mode: 'label'
                            },
                            scales: {
                                xAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: false,
                                        labelString: 'months'
                                    }
                                }],
                                yAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: '# of ' + this.name
                                    }
                                }]
                            },
                            title: {
                                display: true,
                                text: this.name
                            }
                        }
                    };

                        // destroy existing chart

                        if (typeof \$myChart !== "undefined") {
                            \$myChart.destroy();
                        }

                    // set instance, so we can destroy when rendering new chart

                   \$myChart = new Chart( ctx, { type: this.type, data: config.data, options:config.options });
                }

            }


        });


      // new vue instance

    var chart = new Vue ({

        el: '#chart'

    });

    </script>

    <!-- End Chart Script -->
EOD;

        return $this->tokenBuilder->insertTokensIntoContent($content);

    }

    public function AppendChartTemplate()
    {
        $content = <<<EOD
<!-- Begin :::model::: Chart -->

<div id="chart">

<script type="text/x-template" id="graph-template">

    <div class="form-group pull-left">
        <label for="type">chart type:</label>
        <select class="form-control" id="type" v-model="type" v-on:change="changeType">
            <option>line</option>
            <option>bar</option>

        </select>


        <label for="period" id="label">chart periods:</label>
        <select class="form-control" id="period" v-model="period" v-on:change="changePeriod">
            <option value="1year">1 year</option>
            <option value="3months">3 months</option>
            <option value="30days">30 days</option>
            <option value="1week">1 week</option>

        </select>

    </div>

    <canvas id="canvass" width="600" height="400"></canvas>

    </script>

<!-- vue chart component-->

<div id="graph">
    <graph></graph>
</div>

</div>

<!-- End :::model::: Chart -->
EOD;

        return $this->tokenBuilder->insertTokensIntoContent($content);

    }




}