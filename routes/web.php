<?php

use Illuminate\Support\Facades\Route;


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
    // new Helpers\Form\Form;
    return redirect('/dashboard');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('admin/add-helper',function(){
    return view('admin.add-helper');
});

Route::post('register-helper','HelperController@register')->name('register-helper');

Route::get('add-program',function(){
    return view('admin.add-program');
})->name('add-program');

Route::post('add-program','ProgramController@store')->name('add-helper');

Route::get('view-program','ProgramController@show')->name('view-program');

Route::post('deactive-program','ProgramController@deactive')->name('deactive-program');
Route::post('active-program','ProgramController@active')->name('active-program');
Route::post('edit-program','ProgramController@edit')->name('edit-program');
Route::patch('update-program','ProgramController@update')->name('update-program');

Route::get('bank-details','BankController@show')->name('bank-details');
Route::post('add-bank','BankController@store')->name('add-bank');

Route::get('mode-details','ModeController@show')->name('mode-details');
Route::post('add-mode','ModeController@store')->name('add-mode');

Route::view('add-beneficiary', 'add-beneficiary')->name('add-beneficiary');

Route::post('addingbeneficiary', 'BeneficiaryController@addData')->name('addingbeneficiary');
Route::get('add-beneficiary','ProgramController@getName')->name('add-beneficiary');

Route::get('view-beneficiary',function(){
    return view('admin.view-beneficiary');
})->name('view-beneficiary');
Route::get('view-beneficiary', 'BeneficiaryController@showData')->name('view-beneficiary');
Route::get('beneficiaryActiveOrDeactive/{beneficiary_id}/{isactive}', 'BeneficiaryController@activeDeactiveData');
Route::post('edit-beneficiary','BeneficiaryController@editData')->name('edit-beneficiary');
Route::patch('update-beneficiary','BeneficiaryController@updateData')->name('update-beneficiary');

Route::get('add-sponsor','SponsorController@create')->name('add-sponsor');
Route::post('add-sponsor','SponsorController@store')->name('add-sponsor');

Route::post('edit-sponsor','SponsorController@editData')->name('edit-sponsor');
Route::patch('update-sponsor','SponsorController@updateData')->name('update-sponsor');

Route::get('view-sponsor','SponsorController@show')->name('view-sponsor');
Route::post('deactive-sponsor','SponsorController@deactive')->name('deactive-sponsor');
Route::post('active-sponsor','SponsorController@active')->name('active-sponsor');

//Route::post('get-levels','SponsorController@getLevels');
Route::get('add-multiple-sponsor','SponsorController@viewMultipleSponsor')->name('add-multiple-sponsor');

Route::post('sponsor-import', 'SponsorController@sponsorImport')->name('sponsor-import');
Route::get('sponsor-export', 'SponsorController@sponsorExport')->name('sponsor-export');

Route::post('sponsor-program-import', 'SponsorController@sponsorProgramImport')->name('sponsor-program-import');

Route::post('beneficiarie-sponsor-import', 'BeneficiaryController@beneficiareSponsorImport')->name('beneficiarie-sponsor-import');

Route::get('add-multiple-beneficiary','BeneficiaryController@viewMultipleBeneficiary')->name('add-multiple-beneficiary');

Route::post('beneficiarie-import', 'BeneficiaryController@beneficiarieImport')->name('beneficiarie-import');
Route::get('beneficiarie-export', 'BeneficiaryController@beneficiarieExport')->name('beneficiarie-export');

Route::get('searchBeneficiarie','BeneficiaryController@search');
Route::get('searchSponsor','SponsorController@search');






