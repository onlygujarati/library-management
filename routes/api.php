<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API;

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('register',[API\UserController::class, 'register']);
Route::post('login',[API\UserController::class,'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    
    Route::get('book-list', [ API\BookController::class, 'getList'] );
    Route::get('logout',[ API\UserController::class, 'logout']);

    Route::get('book-detail', [ API\BookController::class, 'detail'] );
    Route::post('create-book', [ API\BookController::class, 'store'] );
    Route::post('update-book', [ API\BookController::class, 'update'] );
    Route::post('delete-book', [ API\BookController::class, 'destroy'] );
    
    
    Route::get('my-borrow-book', [ API\BookController::class, 'myBorrowBook'] );
    Route::post('books-borrow', [API\BookController::class, 'borrow']);
    Route::post('books-return', [API\BookController::class, 'returnBook']);

});
