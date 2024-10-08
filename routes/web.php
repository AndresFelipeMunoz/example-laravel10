<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('ajax-crud-datatable', [EmployeeController::class, 'index'])->name('employees.index');
Route::post('/store', [EmployeeController::class, 'store'])->name('store');
Route::get('/edit/{id}', [EmployeeController::class, 'edit'])->name('edit');
Route::post('/edit', [EmployeeController::class, 'edit'])->name('edit');
Route::post('/delete', [EmployeeController::class, 'destroy'])->name('delete');
