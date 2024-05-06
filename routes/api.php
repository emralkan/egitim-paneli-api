<?php

use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\GamesController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PlatformController;
use App\Models\Games;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


        Route::get('translations/{locale}', [LanguageController::class, 'getTranslationsForLocale']);
        Route::get('languages', [LanguageController::class, 'language']);


        Route::post('login' , [UserController::class, 'login']);
        Route::post('register' , [UserController::class, 'register']);
        Route::get('contact-message/{id}', [UserController::class, 'contacts']);
        Route::get('user-delete/{id}', [UserController::class, 'userDelete']);
        Route::post('contact-member', [ContactController::class, 'contactmember']);
        Route::get('contact', [ContactController::class, 'contact']);
        Route::get('contact-message/{id}', [ContactController::class, 'contacts']);



//PLATFORM
        Route::get('platform-login', [PlatformController::class, 'login']);
    Route::group(["middleware" => "auth:api"],function (){

        Route::get('education', [EducationController::class, 'education']);
        Route::get('education-create', [EducationController::class, 'educationCreate']);
        Route::post('education-created', [EducationController::class, 'educationCreated']);
        Route::get('education-update/{id}', [EducationController::class, 'educationUpdate']);
        Route::post('education-updated', [EducationController::class, 'educationUpdated']);
        Route::get('educate-delete/{id}', [EducationController::class, 'educationDelete']);


        Route::get('games', function () {
            $games = Games::all();
            return view('games.index', compact('games'));
        });


        Route::get('games', [GamesController::class, 'games']);
        Route::get('games-create', [GamesController::class, 'gamesCreate']);
        Route::post('games-created', [GamesController::class, 'gamesCreated']);
        Route::get('games-update/{id}', [GamesController::class, 'gamesUpdate']);
        Route::post('games-updated', [GamesController::class, 'gamesUpdated']);
        Route::get('games-delete/{id}', [GamesController::class, 'gamesDelete']);


        Route::get('modules', [ModuleController::class, 'modules']);
        Route::get('module-create', [ModuleController::class, 'moduleCreate']);
        Route::post('module-created', [ModuleController::class, 'moduleCreated']);
        Route::get('module-update/{id}', [ModuleController::class, 'moduleUpdate']);
        Route::post('module-updated', [ModuleController::class, 'moduleUpdated']);
        Route::get('module-delete/{id}', [ModuleController::class, 'moduleDelete']);



        Route::get('language', [LanguageController::class, 'language']);
        Route::post('language-created', [LanguageController::class, 'languageCreated']);
        Route::get('language-update/{id}', [LanguageController::class, 'languageUpdate']);
        Route::post('language-updated', [LanguageController::class, 'languageUpdated']);
        Route::get('language-delete/{id}', [LanguageController::class, 'languageDelete']);



        Route::get('languagesLine', [LanguageController::class, 'languagesLine']);
        Route::get('languagesLine-create', [LanguageController::class, 'languagesLineCreate']);
        Route::post('languagesLine-created', [LanguageController::class, 'languagesLineCreated']);
        Route::get('languagesLine-update/{id}', [LanguageController::class, 'languagesLineUpdate']);
        Route::post('languagesLine-updated', [LanguageController::class, 'languagesLineUpdated']);
        Route::get('languagesLine-delete/{id}', [LanguageController::class, 'languagesLineDelete']);



        Route::get('package-create', [PackageController::class, 'packageCreate']);
        Route::post('package-created', [PackageController::class, 'packageCreated']);
        Route::get('package', [PackageController::class, 'index']);
        Route::get('package/detail/{id}', [PackageController::class, 'detail']);


        Route::post('package-login', [PlatformController::class, 'userLogin']);


        Route::get('dashboard', [UserController::class, 'dashboard']);
        Route::post('logout', [UserController::class, 'logout']);
        Route::get('users', [UserController::class, 'users']);
        Route::get('user-update/{id}', [UserController::class, 'userUpdate']);
        Route::post('user-updated', [UserController::class, 'userUpdated']);

    });
