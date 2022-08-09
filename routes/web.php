<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});


    ;

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/profile', 'HomeController@profile')->name('profile');
Route::get('/profile/edit', 'HomeController@profileEdit')->name('profile.edit');
Route::put('/profile/update', 'HomeController@profileUpdate')->name('profile.update');
Route::get('/profile/changepassword', 'HomeController@changePasswordForm')->name('profile.change.password');
Route::post('/profile/changepassword', 'HomeController@changePassword')->name('profile.changepassword');

Route::group(['middleware' => ['auth','role:Admin']], function () 
{
    Route::get('/roles-permissions', 'RolePermissionController@roles')->name('roles-permissions');
    Route::get('/role-create', 'RolePermissionController@createRole')->name('role.create');
    Route::post('/role-store', 'RolePermissionController@storeRole')->name('role.store');
    Route::get('/role-edit/{id}', 'RolePermissionController@editRole')->name('role.edit');
    Route::put('/role-update/{id}', 'RolePermissionController@updateRole')->name('role.update');

    Route::get('/permission-create', 'RolePermissionController@createPermission')->name('permission.create');
    Route::post('/permission-store', 'RolePermissionController@storePermission')->name('permission.store');
    Route::get('/permission-edit/{id}', 'RolePermissionController@editPermission')->name('permission.edit');
    Route::put('/permission-update/{id}', 'RolePermissionController@updatePermission')->name('permission.update');

    Route::get('assign-subject-to-class/{id}', 'GradeController@assignSubject')->name('class.assign.subject');
    Route::post('assign-subject-to-class/{id}', 'GradeController@storeAssignedSubject')->name('store.class.assign.subject');

   // added
    Route::resource('products', 'ProductsController');
    Route::resource('leads', 'LeadsController');
    Route::resource('units', 'UnitsController');
    Route::resource('credit', 'CreditController');
    Route::resource('customers', 'CustomersController');
    Route::resource('orders', 'OrdersController');
    Route::resource('agreements', 'AgreementsController');
    Route::resource('agreementsF', 'AgreementsFController');
    Route::resource('baskets', 'BasketsController');
    //Route::match(array('GET', 'POST'),'see', 'OrdersController@see')->name('see');
    Route::get('see/{order_id}', 'OrdersController@see')->name('see');
    Route::post('create/{order_id}', 'BasketsController@create')->name('create');
    Route::get('agree/{order_id}', 'OrdersController@agree')->name('agree');
    Route::get('download/{order_id}', 'OrdersController@download')->name('download');
    Route::get('autocomplete', 'OrdersController@autocomplete')->name('autocomplete');
    Route::get('change', 'OrdersController@change')->name('change');
    Route::get('agg/{id}/{customer_id}/{order_id}', 'OrdersController@agg')->name('agg');
    Route::post('file', 'OrdersController@file')->name('file');
    Route::resource('assignrole', 'RoleAssign');
    Route::get('destroy/{id}/{order_id}', 'BasketsController@destroy')->name('destroy');

    Route::any('test','CreditController@test')->name('test');
    Route::any('test1','CreditController@test1')->name('test1');


   
});
// PayME
Route::any('/transfer/by/payme/', [
    'uses' => "PaymeController@transfer",
    'as' => "payme.transfer",
    'middleware' => "payme_auth"
]);

Route::any('/telegram/bot/doktor_shahlo', [
    "uses" => "TelegramBot\DoktorShahloBotController@main",
    "as" => "bot.index"
]);


Route::group(['middleware' => ['auth','role:Admin']], function () 
{
    Route::get('/roles-permissions', 'RolePermissionController@roles')->name('roles-permissions');
    Route::get('/role-create', 'RolePermissionController@createRole')->name('role.create');
    Route::post('/role-store', 'RolePermissionController@storeRole')->name('role.store');
    Route::get('/role-edit/{id}', 'RolePermissionController@editRole')->name('role.edit');
    Route::put('/role-update/{id}', 'RolePermissionController@updateRole')->name('role.update');

    Route::get('/permission-create', 'RolePermissionController@createPermission')->name('permission.create');
    Route::post('/permission-store', 'RolePermissionController@storePermission')->name('permission.store');
    Route::get('/permission-edit/{id}', 'RolePermissionController@editPermission')->name('permission.edit');
    Route::put('/permission-update/{id}', 'RolePermissionController@updatePermission')->name('permission.update');

    Route::get('assign-subject-to-class/{id}', 'GradeController@assignSubject')->name('class.assign.subject');
    Route::post('assign-subject-to-class/{id}', 'GradeController@storeAssignedSubject')->name('store.class.assign.subject');

  

});





Route::get('/', function () {
    return view('welcome');
});

