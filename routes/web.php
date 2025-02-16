<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomersController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('getCustomers', [CustomersController::class, 'getCustomers'])->name('getCustomers');
Route::post('addCustomers', [CustomersController::class, 'addCustomers'])->name('addCustomers');
Route::post('storeCustomers', [CustomersController::class, 'storeCustomers'])->name('storeCustomers');
Route::post('viewCustomers', [CustomersController::class, 'viewCustomers'])->name('viewCustomers');
Route::put('updateCustomers', [CustomersController::class, 'updateCustomers'])->name('updateCustomers');
Route::delete('deleteCustomers', [CustomersController::class, 'deleteCustomers'])->name('deleteCustomers');
Route::get('/export-customers', [CustomersController::class, 'export'])->name('exportCustomers');
Route::post('importCustomers', [CustomersController::class, 'importCustomers'])->name('importCustomers');
Route::post('/import-customers', [CustomersController::class, 'import'])->name('import');
