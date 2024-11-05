<?php

use App\Http\Controllers\InspectionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::prefix('inspection')->group(function () {

    Route::middleware(['auth', 'verified'])->group(function () {

        Route::controller(App\Http\Controllers\InspectionController::class)->group(function () {
            Route::get('/create', 'create')->name('inspection.create');
            Route::get('/', 'index')->name('inspection.index');
            Route::post('/', 'store')->name('inspection.store');
            Route::get('/{inspection}', 'show')->name('inspection.show')->missing(function () {
                return redirect()->back()->with('message', 'Inspeção não encontrada!', 'type', 'alert-danger');
            });
            Route::get('/generate/{inspection}', 'generateXlxs')->name('inspection.generate')->missing(function () {
                return redirect()->back()->with('message', 'Inspeção não encontrada!', 'type', 'alert-danger');
            });
            Route::get('/edit/{inspection}', 'edit')->name('inspection.edit')->missing(function () {
                return redirect()->back()->with('message', 'Inspeção não encontrada!', 'type', 'alert-danger');
            });
            Route::patch('/enabled/{inspection}', 'enabled')->name('inspection.enabled')->missing(function () {
                return redirect()->back()->with('message', 'Inspeção não encontrada!', 'type', 'alert-danger');
            });
            Route::patch('/disabled/{inspection}', 'disabled')->name('inspection.disabled')->missing(function () {
                return redirect()->back()->with('message', 'Inspeção não encontrada!', 'type', 'alert-danger');
            });
            Route::patch('/{inspection}', 'update')->name('inspection.update')->missing(function () {
                return redirect()->back()->with('message', 'Inspeção não encontrada!', 'type', 'alert-danger');
            });
            Route::delete('/{inspection}', 'destroy')->name('inspection.destroy')->missing(function () {
                return redirect()->back()->with('message', 'Inspeção não encontrada!', 'type', 'alert-danger');
            });
            Route::get('/checkresponses/{inspection}', 'checkEveryoneResponded')->name('inspection.everyone')->missing(function () {
                return redirect()->back()->with('message', 'Inspeção não encontrada!', 'type', 'alert-danger');
            });
        });
    });
});

Route::prefix('part')->group(function () {

    Route::middleware(['auth', 'verified'])->group(function () {

        Route::controller(App\Http\Controllers\PartController::class)->group(function () {
            //cadastrar uma peça em uma inspeção
            Route::get('/create/{inspection}', 'create')->name('part.create')->missing(function () {
                return redirect()->back()->with('message', 'Inspeção não encontrada!', 'type', 'alert-danger');
            });
            Route::post('/', 'store')->name('part.store');
            Route::delete('/{part}', 'destroy')->name('part.destroy')->missing(function () {
                return redirect()->back()->with('message', 'Inspeção não encontrada!', 'type', 'alert-danger');
            });
        });
    });
});

Route::prefix('response')->group(function () {

    Route::middleware(['auth', 'verified'])->group(function () {

        Route::controller(App\Http\Controllers\ResponseController::class)->group(function () {
            Route::get('/new/{inspection}', 'create')->name('response.new')->missing(function () {
                return redirect()->back()->with('message', 'Inspeção não encontrada!', 'type', 'alert-danger');
            });
            Route::get('/{inspection}', 'showResposeOperator')->name('respose.show.operator')->missing(function () {
                return redirect()->back()->with('message', 'Inspeção não encontrada!', 'type', 'alert-danger');
            });
            Route::post('/', 'store')->name('response.store');
            // Route::delete('/{part}', 'destroy')->name('part.destroy')->missing(function () {
            //     return redirect()->back()->with('message', 'Inspeção não encontrada!', 'type', 'alert-danger');
            // });
        });
    });
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/help', function () {
    return view('pages/admin/help/index');
})->name('help');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
