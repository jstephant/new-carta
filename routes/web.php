<?php

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

Route::get('/', 'Auth\LoginController@viewLogin')->name('login');
Route::post('/auth/login', 'Auth\LoginController@doLogin')->name('login.post');
Route::get('/auth/logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['prefix' => 'home'], function() {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/lead_status', 'HomeController@getLeadStatus')->name('home.lead.summary');
    Route::get('/followup_status', 'HomeController@getFollowupStatus')->name('home.lead.summary');
    Route::get('/total_account', 'HomeController@getTotalAccount')->name('home.account.summary');
    Route::get('/total_sales', 'HomeController@getTotalSalesSummary')->name('home.sales.summary');
});

Route::group(['prefix' => 'user'], function() {
    Route::get('/search', 'UserController@search')->name('user.search');
});

Route::group(['prefix' => 'ajax'], function() {
    Route::get('/province/{teritory_id}', 'AjaxController@getProvince')->name('get.province');
    Route::get('/city/{province_id}', 'AjaxController@getCity')->name('get.city');
    Route::get('/subdistrict/{city_id}', 'AjaxController@getSubDistrict')->name('get.subdistrict');
    Route::get('/village/{subdistrict_id}', 'AjaxController@getVillage')->name('get.village');
    Route::get('/product', 'AjaxController@getProduct')->name('get.product');
    Route::get('/product_by_type', 'AjaxController@getProductByType')->name('get.product.by.type');
});

Route::group(['prefix' => 'leads'], function() {
    Route::get('/', 'LeadsController@index')->name('leads');
    Route::get('/list', 'LeadsController@getList')->name('leads.list');
    Route::get('/create', 'LeadsController@create')->name('leads.create');
    Route::post('/create', 'LeadsController@doCreate')->name('leads.create.post');
    Route::get('/detail/{id}', 'LeadsController@detail')->name('leads.detail');
    Route::get('/edit/{id}', 'LeadsController@edit')->name('leads.edit');
    Route::post('/edit', 'LeadsController@doUpdate')->name('leads.edit.post');
    Route::post('/import', 'LeadsController@doImport')->name('leads.import.post');
    Route::post('/import-google', 'LeadsController@doImportGoogleSheet')->name('leads.import-google.post');
    Route::get('/delete/{id}', 'LeadsController@doDelete')->name('leads.delete');

    Route::get('/account/{id}', 'LeadsController@viewAccount')->name('leads.view.account');
    Route::get('/download/{type}', 'LeadsController@doDownload')->name('leads.download');
    Route::get('/download-data-filter', 'LeadsController@doDownloadFilter')->name('leads.download-data-filter');
});

Route::group(['prefix' => 'account'], function() {
    Route::get('/', 'AccountController@index')->name('account');
    Route::get('/list', 'AccountController@getList')->name('account.list');
    Route::get('/edit/{id}', 'AccountController@edit')->name('account.edit');
    Route::post('/edit', 'AccountController@doUpdate')->name('account.edit.post');
    Route::get('/detail/{id}', 'AccountController@detail')->name('account.detail');
});

Route::group(['prefix' => 'reward'], function(){
    Route::get('/', 'RewardsController@index')->name('customer-reward');
    Route::get('/detail/{id}', 'RewardsController@detail')->name('customer-reward.detail');
    Route::get('/list', 'RewardsController@listCustomerReward')->name('customer-reward.list');
    Route::get('/find/{id}', 'RewardsController@findById')->name('customer-reward.find');
    Route::post('/approve-reject', 'RewardsController@approvalRedeem')->name('customer-reward.approval');
    Route::get('/delivery-item/{id}', 'RewardsController@deliverRequest')->name('customer-reward.delivery');
    
    Route::get('/sales-approval', 'RewardsController@salesApproval')->name('sales-approval');
    Route::get('/sales-approval/list', 'RewardsController@listSalesApproval')->name('sales-approval.list');
    Route::post('/sales-approval', 'RewardsController@doSalesApproval')->name('sales-approval.post');

    Route::group(['prefix'=>'manage'], function(){
        Route::get('/', 'RewardsController@manageReward')->name('manage-reward');
        Route::get('/create', 'RewardsController@createManageReward')->name('create-manage-reward');
        Route::post('/create', 'RewardsController@doCreateManageReward')->name('create-manage-reward.post');
        Route::get('/list', 'RewardsController@listReward')->name('manage-reward.list');
        Route::get('/delete/{id}', 'RewardsController@doDeleteManageReward')->name('delete-manage-reward.post');

        Route::get('/item', 'RewardsController@manageRewardItem')->name('manage-reward-item');
        Route::get('/item/create', 'RewardsController@createManageRewardItem')->name('create-manage-reward-item');
        Route::post('/item/create', 'RewardsController@doCreateManageRewardItem')->name('create-manage-reward-item.post');
        Route::get('/item/list', 'RewardsController@listRewardItem')->name('manage-reward-item.list');
        Route::get('/item/delete/{id}', 'RewardsController@doDeleteManageRewardItem')->name('delete-manage-reward-item.post');
    });
});

Route::group(['prefix' => 'followup'], function(){
    Route::get('/{access_from}/detail/{id}', 'FollowupController@followup')->name('followup');
    Route::get('/{access_from}/list', 'FollowupController@listFollowup')->name('followup.list');
    Route::get('/{access_from}/photo/{id}', 'FollowupController@followupPhotos')->name('followup.photo');
});

Route::group(['prefix' => 'sales-detail'], function(){
    Route::get('/{access_from}/data/{id}', 'SalesController@salesDetail');
});

Route::group(['prefix' => 'sales-history'], function(){
    Route::get('/{access_from}/detail/{id}', 'SalesController@salesHistory')->name('sales-history');
    Route::get('/{access_from}/list', 'SalesController@listSales')->name('sales-history.list');
});

Route::group(['prefix' => 'redeem-history'], function(){
    Route::get('/{access_from}/detail/{id}', 'RewardsController@listRedeemHistory');
});

Route::group(['prefix' => 'logging'], function(){
    Route::get('/', 'LogController@index');
    Route::get('/list', 'LogController@listLogging');
    Route::get('/download', 'LogController@doDownload');
});

Route::group(['prefix' => 'data-user'], function() {
    Route::get('/', 'UserController@index')->name('user');
    Route::get('list', 'UserController@listUser');
    Route::get('/create', 'UserController@create')->name('user.create');
    Route::post('/create', 'UserController@doCreate')->name('user.create.post');
    Route::get('/edit/{id}', 'UserController@edit')->name('user.edit');
    Route::post('/edit', 'UserController@doUpdate')->name('user.edit.post');
});

Route::group(['prefix' => 'ajax'], function() {
    Route::get('/staff/{nik}', 'UserController@getDetailStaff');
});

Route::group(['prefix' => 'role'], function(){
    Route::get('/', 'RoleController@index')->name('role');
    Route::get('/list', 'RoleController@listRole')->name('role.list');
    Route::get('/list/permission', 'RoleController@listPermission')->name('role.list-permission');
    Route::get('/create', 'RoleController@create')->name('role.create');
    Route::post('/post', 'RoleController@doCreate')->name('role.create.post');
    Route::get('/edit/{id}', 'RoleController@edit')->name('role.edit');
    Route::post('/edit', 'RoleController@doUpdate')->name('role.edit.post');
    Route::post('/delete/{id}', 'RoleController@doDelete')->name('role.delete.post');
});