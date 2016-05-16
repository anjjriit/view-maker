<?php

namespace Evercode1\ViewMaker;


class CrudTemplates
{

    public $tokenBuilder;

    public function __construct(array $tokens)
    {

        $this->tokenBuilder = new CrudTokens($tokens);

    }


    public function controllerTemplate()
    {

        $content = <<<EOD
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
:::useModel:::;
use Illuminate\Support\Facades\Redirect;

class :::upperCaseModelName:::Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view(':::modelPath:::.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view(':::modelPath:::.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  \$request
     * @return \Illuminate\Http\Response
     */
    public function store(Request \$request)
    {

        \$this->validate(\$request, [
            ':::field_name:::' => 'required|unique::::tableName:::|string|max:30',

        ]);

        \$:::modelInstance::: = :::upperCaseModelName:::::create([':::field_name:::' => \$request->:::field_name:::]);
        \$:::modelInstance:::->save();

        return Redirect::route(':::modelPath:::.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  \$id
     * @return \Illuminate\Http\Response
     */
    public function show(\$id)
    {
        \$:::modelInstance::: = :::upperCaseModelName:::::findOrFail(\$id);

        return view(':::modelPath:::.show', compact(':::modelInstance:::'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  \$id
     * @return \Illuminate\Http\Response
     */
    public function edit(\$id)
    {
        \$:::modelInstance::: = :::upperCaseModelName:::::findOrFail(\$id);

        return view(':::modelPath:::.edit', compact(':::modelInstance:::'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  \$request
     * @param  int  \$id
     * @return \Illuminate\Http\Response
     */
    public function update(Request \$request, \$id)
    {
        \$this->validate(\$request, [
            ':::field_name:::' => 'required|string|max:40|unique::::tableName:::,:::field_name:::,' .\$id

        ]);
        \$:::modelInstance::: = :::upperCaseModelName:::::findOrFail(\$id);
        \$:::modelInstance:::->update([':::field_name:::' => \$request->:::field_name:::]);


        return Redirect::route(':::modelPath:::.show', [':::modelInstance:::' => \$:::modelInstance:::]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  \$id
     * @return \Illuminate\Http\Response
     */
    public function destroy(\$id)
    {
        :::upperCaseModelName:::::destroy(\$id);

        return Redirect::route(':::modelPath:::.index');
    }
}
EOD;

        return $this->tokenBuilder->insertTokensIntoContent($content);


    }

    public function childControllerTemplate()
    {

        $content = <<<EOD
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
:::useModel:::;
:::useParent:::;
use Illuminate\Support\Facades\Redirect;

class :::upperCaseModelName:::Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view(':::modelPath:::.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

       \$:::parentInstances::: = :::parent:::::orderBy(':::parentFieldName:::', 'asc')->get();

        return view(':::modelPath:::.create', compact(':::parentInstances:::'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  \$request
     * @return \Illuminate\Http\Response
     */
    public function store(Request \$request)
    {

       \$count = :::parent:::::count();

        \$this->validate(\$request, [
            ':::field_name:::' => 'required|unique::::tableName:::|string|max:30',
            ':::parent_id:::' => "required|numeric|digits_between:1,\$count"

        ]);

        \$:::modelInstance::: = :::upperCaseModelName:::::create([':::field_name:::' => \$request->:::field_name:::,
                                                                  ':::parent_id:::'  => \$request->:::parent_id:::]);
        \$:::modelInstance:::->save();

        return Redirect::route(':::modelPath:::.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  \$id
     * @return \Illuminate\Http\Response
     */
    public function show(\$id)
    {
        \$:::modelInstance::: = :::upperCaseModelName:::::findOrFail(\$id);

         \$:::parentInstance::: = \$:::modelInstance:::->:::parentInstance:::->:::parentFieldName:::;

        return view(':::modelPath:::.show', compact(':::modelInstance:::', ':::parentInstance:::'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  \$id
     * @return \Illuminate\Http\Response
     */
    public function edit(\$id)
    {
        \$:::modelInstance::: = :::upperCaseModelName:::::findOrFail(\$id);

        \$:::parentInstances::: = :::parent:::::orderBy(':::parentFieldName:::', 'asc')->get();

       \$oldValue = \$:::modelInstance:::->:::parentInstance:::->:::parentFieldName:::;

       \$oldId = \$:::modelInstance:::->:::parentInstance:::->id;

        return view(':::modelPath:::.edit', compact(':::modelInstance:::', ':::parentInstances:::', 'oldValue', 'oldId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  \$request
     * @param  int  \$id
     * @return \Illuminate\Http\Response
     */
    public function update(Request \$request, \$id)
    {

        \$count = :::parent:::::count();

        \$this->validate(\$request, [
            ':::field_name:::' => 'required|string|max:40|unique::::tableName:::,:::field_name:::,' .\$id,
            ':::parent_id:::' => "required|numeric|digits_between:1,\$count"

        ]);
        \$:::modelInstance::: = :::upperCaseModelName:::::findOrFail(\$id);
        \$:::modelInstance:::->update([':::field_name:::' => \$request->:::field_name:::,
                                       ':::parent_id:::'  => \$request->:::parent_id:::
                                       ]);

        \$:::parentInstance::: = \$:::modelInstance:::->:::parentInstance:::->:::parentFieldName:::;


        return Redirect::route(':::modelPath:::.show', [':::modelInstance:::' => \$:::modelInstance:::,
                                                        ':::parentInstance:::' => \$:::parentInstance:::
                                                        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  \$id
     * @return \Illuminate\Http\Response
     */
    public function destroy(\$id)
    {
        :::upperCaseModelName:::::destroy(\$id);

        return Redirect::route(':::modelPath:::.index');
    }
}
EOD;

        return $this->tokenBuilder->insertTokensIntoContent($content);


    }

    public function modelTemplate()
    {
        $content = <<<EOD
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class :::upperCaseModelName::: extends Model
{
    protected \$fillable = [':::field_name:::'];
}
EOD;

        return $this->tokenBuilder->insertTokensIntoContent($content);

    }

    public function childModelTemplate()
    {
        $content = <<<EOD
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class :::upperCaseModelName::: extends Model
{
    protected \$fillable = [':::field_name:::', ':::parent_id:::'];

    public function :::parentInstance:::()
   {

       return \$this->belongsTo('App\:::parent:::');
   }
}
EOD;

        return $this->tokenBuilder->insertTokensIntoContent($content);

    }

    public function parentModelTemplate()
    {
        $content = <<<EOD
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class :::upperCaseModelName::: extends Model
{
    protected \$fillable = [':::field_name:::'];

    public function :::childRelation:::()
   {

       return \$this->hasMany('App\:::child:::');
   }

}
EOD;

        return $this->tokenBuilder->insertTokensIntoContent($content);


    }

    public function migrationTemplate()
    {

        $content = <<<EOD
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create:::migrationModel:::Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(':::tableName:::', function (Blueprint \$table) {
            \$table->increments('id');
            \$table->string(':::field_name:::', 30)->unique();
            \$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(':::tableName:::');
    }
}
EOD;

        return $this->tokenBuilder->insertTokensIntoContent($content);
    }

    public function childMigrationTemplate()
    {

        $content = <<<EOD
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create:::childMigrationModel:::Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(':::childsTableName:::', function (Blueprint \$table) {
            \$table->increments('id');
            \$table->string(':::field_name:::', 30)->unique();
            \$table->integer(':::parent_id:::')->unsigned();
            \$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(':::childsTableName:::');
    }
}
EOD;

        return $this->tokenBuilder->insertTokensIntoContent($content);
    }


    public function routeTemplate()
    {

        $content = <<<EOD

// Api Routes

Route::any(':::apiRoute:::', 'ApiController@:::apiControllerMethod:::');
Route::any(':::vueApiRoute:::', 'ApiController@:::vueApiControllerMethod:::');

// :::upperCaseModelName::: Routes

Route::resource(':::modelPath:::', ':::upperCaseModelName:::Controller');
EOD;

        return $this->tokenBuilder->insertTokensIntoContent($content);

    }

    public function factoryTemplate()
    {

        $content = <<<EOD
\n
\$factory->define(App\:::upperCaseModelName:::::class, function (Faker\Generator \$faker) {
    return [
        ':::field_name:::' => \$faker->unique()->word,

    ];
});
EOD;

        return $this->tokenBuilder->insertTokensIntoContent($content);

    }

    public function childFactoryTemplate()
    {

        $content = <<<EOD
\n
\$factory->define(App\:::upperCaseModelName:::::class, function (Faker\Generator \$faker) {
    return [
        ':::field_name:::' => \$faker->unique()->word,
        ':::parent_id:::' => \$faker->numberBetween(\$min = 1, \$max = 4),

    ];
});
EOD;

        return $this->tokenBuilder->insertTokensIntoContent($content);

    }

    public function apiControllerTemplate()
    {

        $content = <<<EOD
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;

class ApiController extends Controller
{

    public function :::apiControllerMethod:::(){

        \$result['data'] = DB::table(':::tableName:::')
                         ->select('id as Id',
                                  ':::field_name::: as Name',
                                  'created_at as Created')
                         ->get();

        return json_encode(\$result);

    }

    public function :::vueApiControllerMethod:::(Request \$request){

        \$column = 'id';
        \$direction = 'asc';

        if (\$request->has('column')){

            \$column = \$request->get('column');
            if (\$column == 'Id'){
                \$direction = \$request->get('direction') == 1 ? 'asc' : 'desc';
            } else {

                \$direction = \$request->get('direction') == 1 ? 'desc' : 'asc';
            }


        }

        if (\$request->has('keyword')){

            \$keyword = \$request->get('keyword');

            \$:::modelResults::: = DB::table(':::tableName:::')
                ->select('id as Id',
                    ':::field_name::: as Name',
                    'created_at as Created')
                ->where(':::field_name:::', 'like', '%' . \$keyword . '%')
                ->orderBy(\$column, \$direction)
                ->paginate(10);

            return response()->json(\$:::modelResults:::);



        }

        \$:::modelResults::: = DB::table(':::tableName:::')
                             ->select('id as Id',
                                      ':::field_name::: as Name',
                                      'created_at as Created')
                             ->orderBy(\$column, \$direction)
                             ->paginate(10);

        return response()->json(\$:::modelResults:::);

    }


}

EOD;

        return $this->tokenBuilder->insertTokensIntoContent($content);

    }

    public function childApiControllerTemplate()
    {

        $content = <<<EOD
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;

class ApiController extends Controller
{

    public function :::apiControllerMethod:::(){

        \$result['data'] = DB::table(':::tableName:::')
                         ->select(':::tableName:::.id as Id',
                                  ':::field_name::: as Name',
                                  ':::parentFieldName::: as :::parent:::',
                                  ':::tableName:::.created_at as Created')
                         ->leftJoin(':::parentsTableName:::', ':::parent_id:::', '=', ':::parentsTableName:::.id')
                         ->get();

        return json_encode(\$result);

    }

    public function :::vueApiControllerMethod:::(Request \$request){

        \$column = 'id';
        \$direction = 'asc';

        if (\$request->has('column')){

            \$column = \$request->get('column');
            if (\$column == 'Id'){
                \$direction = \$request->get('direction') == 1 ? 'asc' : 'desc';
            } else {

                \$direction = \$request->get('direction') == 1 ? 'desc' : 'asc';
            }


        }

        if (\$request->has('keyword')){

            \$keyword = \$request->get('keyword');

            \$:::modelResults::: = DB::table(':::tableName:::')
                ->select(':::tableName:::.id as Id',
                         ':::field_name::: as Name',
                         ':::parentFieldName::: as :::parent:::'
                         ':::tableName:::.created_at as Created')
                ->leftJoin(':::parentsTableName:::', ':::parent_id:::', '=', ':::parentsTableName:::.id')
                ->where(':::field_name:::', 'like', '%' . \$keyword . '%')
                ->orderBy(\$column, \$direction)
                ->paginate(10);

            return response()->json(\$:::modelResults:::);



        }

        \$:::modelResults::: = DB::table(':::tableName:::')
                             ->select(':::tableName:::.id as Id',
                                      ':::field_name::: as Name',
                                      ':::parentFieldName::: as :::parent:::'
                                      ':::tableName:::.created_at as Created')
                             ->leftJoin(':::parentsTableName:::', ':::parent_id:::', '=', ':::parentsTableName:::.id')
                             ->orderBy(\$column, \$direction)
                             ->paginate(10);

        return response()->json(\$:::modelResults:::);

    }


}

EOD;

        return $this->tokenBuilder->insertTokensIntoContent($content);

    }

    public function appendApiControllerTemplate()
    {

        $content = <<<EOD
    public function :::apiControllerMethod:::(){

        \$result['data'] = DB::table(':::tableName:::')
                         ->select('id as Id',
                                  ':::field_name::: as Name',
                                  'created_at as Created')
                         ->get();

        return json_encode(\$result);

    }

    public function :::vueApiControllerMethod:::(Request \$request){

        \$column = 'id';
        \$direction = 'asc';

        if (\$request->has('column')){

            \$column = \$request->get('column');
            if (\$column == 'Id'){
                \$direction = \$request->get('direction') == 1 ? 'asc' : 'desc';
            } else {

                \$direction = \$request->get('direction') == 1 ? 'desc' : 'asc';
            }


        }

        if (\$request->has('keyword')){

            \$keyword = \$request->get('keyword');

            \$:::modelResults::: = DB::table(':::tableName:::')
                ->select('id as Id',
                    ':::field_name::: as Name',
                    'created_at as Created')
                ->where(':::field_name:::', 'like', '%' . \$keyword . '%')
                ->orderBy(\$column, \$direction)
                ->paginate(10);

            return response()->json(\$:::modelResults:::);



        }

        \$:::modelResults::: = DB::table(':::tableName:::')
                             ->select('id as Id',
                                      ':::field_name::: as Name',
                                      'created_at as Created')
                             ->orderBy(\$column, \$direction)
                             ->paginate(10);

        return response()->json(\$:::modelResults:::);

    }
EOD;

        return $this->tokenBuilder->insertTokensIntoContent($content);

    }

    public function childAppendApiControllerTemplate()
    {

        $content = <<<EOD
    public function :::apiControllerMethod:::(){

    \$result['data'] = DB::table(':::tableName:::')
                         ->select(':::tableName:::.id as Id',
                                  ':::field_name::: as Name',
                                  ':::parentFieldName::: as :::parent:::',
                                  ':::tableName:::.created_at as Created')
                         ->leftJoin(':::parentsTableName:::', ':::parent_id:::', '=', ':::parentsTableName:::.id')
                         ->get();

        return json_encode(\$result);

    }

    public function :::vueApiControllerMethod:::(Request \$request){

        \$column = 'id';
        \$direction = 'asc';

        if (\$request->has('column')){

            \$column = \$request->get('column');
            if (\$column == 'Id'){
                \$direction = \$request->get('direction') == 1 ? 'asc' : 'desc';
            } else {

                \$direction = \$request->get('direction') == 1 ? 'desc' : 'asc';
            }


        }

        if (\$request->has('keyword')){

            \$keyword = \$request->get('keyword');

            \$:::modelResults::: = DB::table(':::tableName:::')
                ->select(':::tableName:::.id as Id',
                         ':::field_name::: as Name',
                         ':::parentFieldName::: as :::parent:::',
                         ':::tableName:::.created_at as Created')
                ->leftJoin(':::parentsTableName:::', ':::parent_id:::', '=', ':::parentsTableName:::.id')
                ->where(':::field_name:::', 'like', '%' . \$keyword . '%')
                ->orderBy(\$column, \$direction)
                ->paginate(10);

            return response()->json(\$:::modelResults:::);



        }

        \$:::modelResults::: = DB::table(':::tableName:::')
                             ->select(':::tableName:::.id as Id',
                                      ':::field_name::: as Name',
                                      ':::parentFieldName::: as :::parent:::',
                                      ':::tableName:::.created_at as Created')
                             ->leftJoin(':::parentsTableName:::', ':::parent_id:::', '=', ':::parentsTableName:::.id')
                             ->orderBy(\$column, \$direction)
                             ->paginate(10);

        return response()->json(\$:::modelResults:::);

    }
EOD;

        return $this->tokenBuilder->insertTokensIntoContent($content);

    }

    public function testTemplate()
    {

        $content = <<<EOD
<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class :::upperCaseModelName:::Test extends TestCase
{
    public function testCreateNew:::upperCaseModelName:::()
    {

        \$randomString = str_random(10);

        \$this->visit('/:::modelPath:::/create')
              ->type(\$randomString, ':::field_name:::')
              ->press('Create')
              ->seePageIs('/:::modelPath:::');
    }
}
EOD;

        return $this->tokenBuilder->insertTokensIntoContent($content);

    }

    public function childTestTemplate()
    {

        $content = <<<EOD
<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class :::upperCaseModelName:::Test extends TestCase
{
    public function testCreateNew:::upperCaseModelName:::()
    {

        \$randomString = str_random(10);

        \$this->visit('/:::modelPath:::/create')
              ->type(\$randomString, ':::field_name:::')
              ->select(1, ':::parent_id:::')
              ->press('Create')
              ->seePageIs('/:::modelPath:::');
    }
}
EOD;

        return $this->tokenBuilder->insertTokensIntoContent($content);

    }

}


