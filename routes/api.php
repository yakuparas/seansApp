<?php

use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EventTypeController;
use App\Http\Controllers\Api\UserScheduleController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ForgotPasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

    Route::post('/login',[LoginController::class, 'login'])->name('user.login');
    Route::post('/customer/register',[RegisterController::class, 'customerRegister'])->name('customer.register');
    Route::post('/customer/verify',[RegisterController::class, 'emailVerify'])->name('customer.emailVerify');

    Route::post('/sendCode',[ForgotPasswordController::class, 'sendCode'])->name('sendcode');
    Route::post('/updatePassword',[ForgotPasswordController::class, 'updatePassword'])->name('sendcode');


    Route::get('country', function () {
             return \App\Models\Country::all();
        });

        Route::get('city/{id}', function ($id) {
            return \App\Models\Citie::where('state_id',$id)->get();
        });

        Route::get('state/{id}', function ($id) {
            return \App\Models\State::where('country_id',$id)->get();
        });


    Route::middleware('auth:api')->group(function(){
        Route::post('/logout',[LoginController::class, 'logout'])->name('user.logout');
        Route::prefix('admin')->group(function(){

            Route::controller(CompanyController::class)->group(function (){
                Route::get('company','index')->name('company.list');
                Route::post('addCompany','store')->name('company.store');
                Route::get('getCompany/{id}','show')->name('company.show');
                Route::post('updateCompany/{id}','update')->name('company.update');
                Route::post('destroyCompany/{id}','destroy')->name('company.destroy');
            });

            Route::controller(EventTypeController::class)->group(function (){
                Route::get('getEventType','index')->name('eventType.list');
                Route::post('addEventType','store')->name('eventType.store');
                Route::get('getEventType/{id}','show')->name('eventType.show');
                Route::post('updateEventType/{id}','update')->name('eventType.update');
                Route::post('destroyEventType/{id}','destroy')->name('eventType.destroy');
            });

        });
        Route::prefix('company')->group(function (){

            Route::controller(StoreController::class)->group(function (){
                Route::get('getStores','index')->name('store.index');
                Route::post('addStore','store')->name('store.store');
                Route::get('getStore/{id}','show')->name('store.show');
                Route::post('updateStore/{id}','update')->name('store.update');
                Route::post('destroyStore/{id}','destroy')->name('store.destroy');
            });

            Route::controller(UserController::class)->prefix('store/user')->group(function (){
                Route::get('getUsers','index')->name('user.list');
                Route::post('addUser','store')->name('user.store');
                Route::get('getUser/{id}','show')->name('user.show');
                Route::post('updateUser/{id}','update')->name('user.update');
                Route::post('destroyUser/{id}','destroy')->name('user.destroy');
            });

            Route::controller(RoleController::class)->prefix('store/role')->group(function () {
                Route::get('getPermissions','getPermission')->name('getPermissions');
                Route::get('getRole','index')->name('role.index');
                Route::post('addRole','store')->name('role.store');
                Route::get('getRole/{id}','show')->name('role.show');
                Route::post('updateRole/{id}','update')->name('role.update');
                Route::post('destroyRole/{id}','destroy')->name('role.destroy');
            });


            Route::controller(ServiceController::class)->prefix('store/services')->group(function (){
                Route::get('getServices','index')->name('services.index');
                Route::post('addServices','store')->name('services.store');
                Route::get('getServices/{id}','show')->name('services.show');
                Route::post('updateServices/{id}','update')->name('services.update');
                Route::post('destroyServices/{id}','destroy')->name('services.destroy');
            });






        });
        Route::controller(UserScheduleController::class)->group(function (){
            Route::get('getUserSchedulers/{id}','index')->name('userSchedule.list');
            Route::post('addUserSchedule','store')->name('userSchedule.store');
            Route::get('getUserSchedule/{id}','show')->name('userSchedule.show');
            Route::post('updateUserSchedule/{id}','update')->name('userSchedule.update');
            Route::post('destroyUserSchedule/{id}','destroy')->name('userSchedule.destroy');

        });

        Route::prefix('customer')->group(function (){

            Route::controller(EventController::class)->group(function (){
                Route::get('getEvents','index')->name('customerEventList.list');
                Route::post('addEvent','store')->name('customerAddEvent.store');
                Route::get('getEvent','show')->name('customerEvent.show');
                Route::post('destroyEvent','destroy')->name('customerEvent.destroy');
            });

            Route::get('/getProfile',[CustomerController::class, 'getProfile'])->name('customer.profile');



        });
        Route::controller(CustomerController::class)->group(function (){
            Route::get('getCustomers/{id}','index')->name('customer.list');
            Route::get('getCustomer/{id}','show')->name('customer.show');
            Route::post('updateCustomer','update')->name('customer.update');
            Route::post('changeCustomerStatus/{id}','status')->name('customerStatus.status');
            Route::post('destroyCustomer/{id}','destroy')->name('customer.destroy');
        });
    });





