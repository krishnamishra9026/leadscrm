<?php
Route::redirect('/', 'admin/home');

Auth::routes(['register' => false]);

// Change Password Routes...
Route::get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
Route::patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password');

Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('permissions', 'Admin\PermissionsController');
    Route::delete('permissions_mass_destroy', 'Admin\PermissionsController@massDestroy')->name('permissions.mass_destroy');
    Route::resource('roles', 'Admin\RolesController');
    Route::delete('roles_mass_destroy', 'Admin\RolesController@massDestroy')->name('roles.mass_destroy');
    Route::resource('users', 'Admin\UsersController');
    Route::delete('users_mass_destroy', 'Admin\UsersController@massDestroy')->name('users.mass_destroy');
    Route::resource('lead-users', 'Admin\LeadUserController');
    Route::get('lead-users/show-all/{id}', 'Admin\LeadUserController@showAll')->name('lead-users.show-all');
    Route::get('changeStatus', 'Admin\LeadUserController@ChangeLeadStatus');
    Route::delete('lead_users_mass_destroy', 'Admin\LeadUserController@massDestroy')->name('lead-users.mass_destroy');
    Route::resource('leads', 'Admin\LeadController');
    Route::get('leads/view/{id}', 'Admin\LeadController@view')->name('leads.view');
    Route::get('leads/show-all/{id}', 'Admin\LeadController@showAll')->name('leads.show-all');
    Route::delete('leads_mass_destroy', 'Admin\LeadController@massDestroy')->name('leads.mass_destroy');
    Route::resource('comments', 'Admin\CommentController');
});
