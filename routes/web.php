<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\LocationController;

Route::get('/', function(){ return redirect()->route('login'); });

Auth::routes(['register' => false]);

Route::middleware(['auth','profile.completed'])->group(function(){
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::resource('parents', ParentController::class);
    Route::resource('children', ChildController::class);

    Route::post('parents/{parent}/link-children', [ParentController::class,'linkChildren'])->name('parents.linkChildren');
    Route::post('children/{child}/link-parents', [ChildController::class,'linkParents'])->name('children.linkParents');
});

Route::get('/profile/complete', [AdminController::class,'showCompleteProfile'])->name('profile.complete');
Route::post('/profile/complete', [AdminController::class,'completeProfile'])->name('profile.complete.store');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/get-states/{country}', [LocationController::class, 'states']);
Route::get('/get-cities/{state}',   [LocationController::class, 'cities']);