<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\longToShortAndSaveRedirect;
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
    return view('welcome');
});
//->where('t', '[A-Za-z0-9]+')
Route::post('/long-to-short-and-save-redirect', [longToShortAndSaveRedirect::class, 'lToSAndSR']);
Route::get('/{t}', [longToShortAndSaveRedirect::class, 'redirectToLong']);
