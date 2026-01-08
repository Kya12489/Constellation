<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AssociationController;

Route::get('/', [AssociationController::class, 'listeAssociations']);
Route::get('/association/insert/{rnaId}', [AssociationController::class, 'createAssociation']);
Route::get('/association/{rnaId}', [AssociationController::class, 'detailAssociation']);
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/association/{rnaId}/comment', [AssociationController::class, 'storeComment'])->name('associations.comment.store');
    Route::put('/association/{rnaId}/comment/{commentId}', [AssociationController::class, 'updateComment'])->name('associations.comment.update');
    Route::delete('/association/{rnaId}/comment/{commentId}', [AssociationController::class, 'deleteComment'])->name('associations.comment.delete');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
