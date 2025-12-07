<?php

use App\Modules\Admin\Articles\Controllers\AdminAlignMediaController;
use App\Modules\Admin\Articles\Controllers\AdminArticleCategoryController;
use App\Modules\Admin\Articles\Controllers\AdminArticleController;
use App\Modules\Admin\Articles\Controllers\AdminArticleTagController;
use App\Modules\Admin\Articles\Controllers\AdminTopArticleController;
use App\Modules\Admin\Articles\Controllers\Auth\AdminLoginController;
use App\Modules\Admin\ContributorTrainigs\Controllers\AdminContributorTrainingController;
use App\Modules\Admin\Information\Controllers\InformationController;
use App\Modules\Admin\Inquiry\Controllers\InquiryController;
use App\Modules\Admin\Organizations\Controllers\AdminOrganizationController;
use App\Modules\Admin\Users\Controllers\AdminUserController;
use App\Modules\Articles\Controllers\ArticleController;
use App\Modules\Company\Controllers\CompanyController;
use App\Modules\Event\Controllers\EventController;
use App\Modules\User\Controllers\UserContributorTrainingsController;
use App\Modules\Weather\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

Route::post('login', AdminLoginController::class);

Route::prefix('company')->group(function () {
    Route::get('', [CompanyController::class, 'getCompany']);
    Route::get('tags', [AdminArticleTagController::class, 'index']);
    Route::post('tags', [AdminArticleTagController::class, 'store']);
});

Route::middleware('auth:sanctum', 'admin')->group(function () {

    Route::get('/download-log', function () {
        $logPath = storage_path('logs/laravel.log');
    
        if (!file_exists($logPath)) {
            abort(404, 'Log file not found.');
        }
    
        return Response::download($logPath, 'laravel.log');
    });

    Route::get('dashboard', [CompanyController::class, 'dashboard']);

    Route::prefix('info')->group(function () {
        Route::patch('', [CompanyController::class, 'update']);
    });

    Route::prefix('articles')->group(function () {
        Route::get('/', [AdminArticleController::class, 'index']);
        Route::get('/{id}', [AdminArticleController::class, 'show']);
        Route::post('', [AdminArticleController::class, 'store']);
        Route::delete('/{id}', [AdminArticleController::class, 'delete']);
        Route::get('/export/distribution-point', [AdminUserController::class, 'distributionpointCSV']);

        //submit
        Route::post('{id}/submit', [ArticleController::class, 'save'])->name('articles.submit');

        //save
        Route::post('{id}/save', [ArticleController::class, 'save'])->name('articles.save');

        Route::post('{id}/remand', [AdminArticleController::class, 'remand']);
        Route::get('{id}/remand', [AdminArticleController::class, 'articleToRemand']);
        Route::post('{id}/approved', [AdminArticleController::class, 'approved']);
        Route::post('{id}/edit-approved', [AdminArticleController::class, 'editApproved']);
        Route::delete('{id}/delete-approved', [AdminArticleController::class, 'deleteApproved']);
        Route::delete('{id}/request-withdrawal' , [ArticleController::class,'withdrawalOfRequest']);
        Route::get('/status/all', [AdminArticleController::class, 'getStatus']);
        Route::get('/to/top', [AdminArticleController::class, 'searchToTop']);
    });

    Route::prefix('categories')->group(function () {
        Route::get('', [AdminArticleCategoryController::class, 'index']);
        Route::delete('/{id}', [AdminArticleCategoryController::class, 'delete']);
        Route::get('/{id}', [AdminArticleCategoryController::class, 'show']);
        Route::put('/{id}', [AdminArticleCategoryController::class, 'update']);
        Route::post('', [AdminArticleCategoryController::class, 'storeArticleCategory']);
    });

    Route::prefix('top-articles')->group(function () {
        Route::get('', [AdminTopArticleController::class, 'index']);
        Route::delete('/{id}', [AdminTopArticleController::class, 'delete']);
        Route::get('/{id}', [AdminTopArticleController::class, 'show']);
        Route::put('/{id}', [AdminTopArticleController::class, 'update']);
        Route::post('', [AdminTopArticleController::class, 'store']);
    });

    Route::prefix('users')->group(function () {
        Route::get('', [AdminUserController::class, 'index']);
        Route::delete('/{id}', [AdminUserController::class, 'delete']);
        Route::put('/{id}', [AdminUserController::class, 'update'])->name('admin.edit.users');
        Route::get('/{id}', [AdminUserController::class, 'show']);
        Route::post('{id}/contributor-acknowledge', [AdminUserController::class, 'contributorAcknowledge']);
        Route::get('/admin/export', [AdminUserController::class, 'export']);
        Route::get('/contributor/status', [UserContributorTrainingsController::class, 'contributorStatus']);
    });

    Route::prefix('align-media')->group(function () {
        Route::get('', [AdminAlignMediaController::class, 'index']);
        Route::get('/{id}', [AdminAlignMediaController::class, 'show']);
        Route::post('', [AdminAlignMediaController::class, 'store']);
        Route::put('{id}', [AdminAlignMediaController::class, 'update']);
        Route::delete('{id}', [AdminAlignMediaController::class, 'delete']);
    });

    Route::prefix('tag-articles')->group(function () {
        Route::post('', [AdminArticleTagController::class, 'store']);
    });

    Route::prefix('organizations')->group(function() {
        Route::get('' ,[AdminOrganizationController::class,'index']);
        Route::get('{id}' ,[AdminOrganizationController::class,'show']);
        Route::post('' ,[AdminOrganizationController::class,'store'])->name('admin.organizations.create');
        Route::put('{id}' ,[AdminOrganizationController::class,'update'])->name('admin.organizations.update');
        Route::delete('{id}' ,[AdminOrganizationController::class,'delete']);

        Route::post('{id}/users/{userId}/approve', [AdminOrganizationController::class, 'affilliateApproved']);
        Route::post('{id}/users/{userId}/withdraw-approve', [AdminOrganizationController::class, 'affilliateWithdrawApproved']);
    });

    Route::prefix('events')->group(function () {
        Route::get('/search', [EventController::class,'index']);
        Route::get('search/org', [CompanyController::class, 'search']);
        Route::post('', [EventController::class, 'store']);
        Route::get('/{id}', [EventController::class, 'show'])->name('show.event');
        Route::put('{id}', [EventController::class, 'update']);
    });

    Route::prefix('contributor-trainings')->group(function () {
        Route::get('', [AdminContributorTrainingController::class, 'index']);
        Route::get('{id}', [AdminContributorTrainingController::class, 'show']);
        Route::post('', [AdminContributorTrainingController::class, 'store']);
        Route::put('{id}', [AdminContributorTrainingController::class, 'update']);
        Route::delete('{id}', [AdminContributorTrainingController::class, 'destroy']);
    });

    Route::prefix('information')->group(function () {
        Route::get('', [InformationController::class, 'index']);
        Route::post('', [InformationController::class, 'store']);
        Route::put('{id}', [InformationController::class, 'update']);
        Route::get('{id}', [InformationController::class, 'show']);
        Route::delete('{id}', [InformationController::class, 'delete']);
    });

    Route::prefix('inquiries')->group(function () {
        Route::get('', [InquiryController::class, 'index']);
        Route::put('{id}', [InquiryController::class, 'update']);
        Route::get('{id}', [InquiryController::class, 'show']);
        Route::delete('{id}', [InquiryController::class, 'delete']);
    });

    Route::prefix('weather')->group(function () {
        Route::post('', [WeatherController::class, 'store']);
        Route::get('{id}', [WeatherController::class, 'show']);
        Route::put('{id}', [WeatherController::class, 'update']);

    });

    Route::prefix('company')->group(function () {
        Route::put('/post-limit', [CompanyController::class, 'updatePostLimit']);
    });
});
