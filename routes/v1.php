<?php

use App\Enums\Throttle;
use App\Http\ApiResponse\ApiResponse;
use App\Http\Controllers\FieldValidationController;
use App\Http\Controllers\V1\Auth\LoginController;
use App\Http\Controllers\V1\Auth\RegisterController;
use App\Http\Controllers\V1\Auth\VerifyEmailController;
use App\Http\Controllers\V1\Invoke\ArticleRankingController;
use App\Http\Controllers\V1\Invoke\SearchArticleController;
use App\Http\Controllers\V1\Invoke\TopNavCategoryController;
use App\Http\Controllers\V1\Media\MediaController;
use App\Modules\Admin\Information\Controllers\InformationController;
use App\Modules\Admin\Information\Models\Informations;
use App\Modules\Admin\Inquiry\Controllers\InquiryController;
use App\Modules\Articles\Controllers\ArticleController;
use App\Modules\Company\Controllers\CategoryController;
use App\Modules\Company\Controllers\CompanyController;
use App\Modules\Company\Controllers\OrganizationController;
use App\Modules\Company\Controllers\TagController;
use App\Modules\User\Controllers\Auth\SocialLoginController;
use App\Modules\User\Controllers\ChangePasswordController;
use App\Modules\User\Controllers\ContributorTrainingsController;
use App\Modules\User\Controllers\RequestLoginIdController;
use App\Modules\User\Controllers\ResetPasswordController;
use App\Modules\User\Controllers\SocialController;
use App\Modules\User\Controllers\UserContributorTrainingsController;
use App\Modules\User\Controllers\UserController;
use App\Modules\Weather\Controllers\WeatherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

Route::post('validate',[FieldValidationController::class, 'validateField']);

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', LoginController::class)->middleware(Throttle::LOW);
    Route::post('login/social', SocialLoginController::class)->middleware(Throttle::LOW);

    Route::post('register', RegisterController::class);
    Route::post('check/login-id', function( Request $request) {
        $request->validate([
            'login_id' => ['required' ,'string' , Rule::unique('users','login_id')->withoutTrashed(),'max:100']
        ],[
            'login_id.required' => __('login_id.required'),
            'login_id.unique' => __('login_id.unique'),
            'login_id.max' => __('login_id.max'),
        ]);

        return ApiResponse::success($request->login_id);
    })->middleware(Throttle::MEDIUM);

    Route::get('email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])->middleware(['signed'])->name('verification.verify');
    Route::post('email/resend', [VerifyEmailController::class, 'resend'])->middleware(['auth:api'])->name('verification.resend');
});

Route::post('media/upload' , MediaController::class);

Route::post('inquiry', [InquiryController::class, 'store']);
Route::get('inquiry-types', [InquiryController::class, 'inquiryTypes']);
Route::get('notice', [InformationController::class, 'index']);
Route::get('notice/{id}', [InformationController::class, 'show']);
Route::get('weather', [WeatherController::class, 'getWeather']);
Route::get('social/register' , [SocialController::class,'linkSocialRegister']);

Route::middleware(['company'])->group(function () {

    Route::post('profile/request-reset-password' , [ResetPasswordController::class,'checkCredentials'])->middleware('throttle:100,1');
    Route::post('profile/reset-password' , [ResetPasswordController::class,'resetPassword']);
    Route::post('profile/request-login-id' , RequestLoginIdController::class)->middleware('throttle:10,1');

    
    Route::middleware(['auth:sanctum'])->group(function() {
        Route::prefix('profile')->group(function() {
            Route::get('', [UserController::class, 'me'])->name('user.profile');
            Route::post('change-password', ChangePasswordController::class)->name('user.change-password');
            Route::put('update', [UserController::class,'update'])->name('user.update');
        });

        Route::post('organizations/{id}/withdrawal', [UserController::class, 'affiliateWithdraw']);

        Route::prefix('user')->middleware(['auth:sanctum'])->group(function() {
            Route::get('articles', [UserController::class , 'articles'])->name('user.articles');
            Route::get('articles/count', [UserController::class,'count']);
            Route::get('articles/{id}' , [UserController::class,'article']);
            Route::put('articles/{id}' , [ArticleController::class,'update']);
            Route::put('contributor-application' , [UserController::class, 'contributorApply']);

            Route::get('article/status', [ArticleController::class , 'getStatus']);

            // create
            Route::post('articles' , [ArticleController::class,'store'])->middleware(['company.post.limit']);

            //submit
            Route::post('articles/{id}/submit' , [ArticleController::class,'save'])->middleware(['company.post.limit'])->name('articles.submit');

            //save
            Route::post('articles/{id}/save' , [ArticleController::class,'save'])->name('articles.save');

            // delete request logically
            Route::post('articles/{id}/delete-request' , [ArticleController::class,'deleteRequest']);

            // delete logically
            Route::delete('articles/{id}' , [ArticleController::class,'destroy']);

            // withdrawal of requests
            Route::delete('articles/{id}/request-withdrawal' , [ArticleController::class,'withdrawalOfRequest']);

            Route::put('social' , [SocialController::class,'linkSocial']);
            Route::put('social/unlink' , [SocialController::class,'unlinkSocial']);
            Route::delete('articles/{articleId}/media/{mediaId}/delete' ,[ArticleController::class,'removeArticleMedia']);
            
        });

    });
    
    Route::resource('organization', OrganizationController::class)->middleware([Throttle::MEDIUM]);
    
    Route::prefix('articles')->group(function() {
        Route::get('', [ArticleController::class,'index']);
        Route::get('/{id}', [ArticleController::class,'show'])->name('public.article')->middleware(['article.view.logs']);
    });
    
    Route::get('rank/article' , ArticleRankingController::class)->middleware(Throttle::HIGH);
    Route::get('search', SearchArticleController::class)->middleware(Throttle::HIGH); 
    Route::resource('tag', TagController::class)->middleware(Throttle::HIGH);
    
    Route::group(['prefix' => 'category'], function () {
        Route::get('', TopNavCategoryController::class)->middleware(Throttle::HIGH);
        Route::get('articles', [CategoryController::class, 'index'])->middleware(Throttle::MEDIUM);
        Route::get('{id}', [CategoryController::class, 'show'])->middleware(Throttle::MEDIUM);
        Route::get('/name/{name}', [CategoryController::class, 'showByName'])->middleware(Throttle::MEDIUM);
    });

    Route::group(['prefix' => 'company'] , function() {
        Route::get('alignment-media' , [CompanyController::class, 'alignmentMedia']);

        Route::get('categories', [CategoryController::class,'categories'])->middleware(['auth:sanctum']);
    });
});

Route::middleware('auth:sanctum')->group(function(){
    Route::prefix('contributor-trainings')->group(function(){
        Route::get('' , [ContributorTrainingsController::class, 'index'])->middleware('contributor.traning.access');
        Route::post('' , [ContributorTrainingsController::class, 'store']);
    });

    Route::prefix('user-contributor-trainings')->group(function(){
        Route::post('' , [UserContributorTrainingsController::class, 'store']);
    });
});

