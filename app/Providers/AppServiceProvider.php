<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use App\Modules\Admin\AffiliatedMedia\Models\AffiliatedMedia;
use App\Modules\Admin\AffiliatedMedia\Repositories\AffiliatedMediaRepository;
use App\Modules\Admin\Inquiry\Models\Inquiries;
use App\Modules\Admin\Inquiry\Models\Inquiry;
use App\Modules\Admin\Inquiry\Repositories\InquiryRepository;
use App\Modules\Articles\Models\Article;
use App\Modules\Articles\Models\ArticleCategory;
use App\Modules\Articles\Models\ArticleTag;
use App\Modules\Articles\Models\RevisedArticle;
use App\Modules\Articles\Models\TopArticle;
use App\Modules\Articles\Repositories\ArticleRepository;
use App\Modules\Articles\Repositories\TopArticleRepository;
use App\Modules\Company\Models\Company;
use App\Modules\Company\Models\Organization;
use App\Modules\Company\Repositories\CategoryRepository;
use App\Modules\Company\Repositories\CompanyRepository;
use App\Modules\Company\Repositories\OrganizationRepository;
use App\Modules\Company\Repositories\TagRepository;
use App\Repositories\MediaRepository;
use App\Modules\Articles\Repositories\ArticleCategoryRepository;
use App\Modules\Articles\Repositories\ArticleRevisedRepository;
use App\Modules\User\Models\User;
use App\Policies\ArticlePolicy;
use App\Repositories\UserRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
{
    $this->app->singleton(UserRepository::class, function ($app) {
        return new UserRepository(new User());
    });

    $this->app->singleton(CompanyRepository::class, function ($app) {
        return new CompanyRepository(new Company());
    });

    $this->app->singleton(OrganizationRepository::class, function ($app) {
        return new OrganizationRepository(new Organization());
    });

    $this->app->singleton(ArticleRepository::class, function ($app) {
        return new ArticleRepository(new Article());
    });

    $this->app->singleton(MediaRepository::class, function ($app) {
        return new MediaRepository(new \Spatie\MediaLibrary\MediaCollections\Models\Media());
    });

    $this->app->singleton(TopArticleRepository::class, function ($app) {
        return new TopArticleRepository(new TopArticle());
    });

    $this->app->singleton(CategoryRepository::class, function ($app) {
        return new CategoryRepository(new ArticleCategory());
    });

    $this->app->singleton(TagRepository::class, function ($app) {
        return new TagRepository(new ArticleTag());
    });

    $this->app->singleton(ArticleCategoryRepository::class, function ($app) {
        return new ArticleCategoryRepository(new ArticleCategory());
    });

    $this->app->singleton(ArticleRevisedRepository::class, function ($app) {
        return new ArticleRevisedRepository(new RevisedArticle());
    });

    $this->app->singleton(InquiryRepository::class, function ($app) {
        return new InquiryRepository(new Inquiry());
    });

    if ($this->app->environment('local')) {
        $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        $this->app->register(TelescopeServiceProvider::class);
    }
}


    

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Article::class, ArticlePolicy::class);

        Gate::before(function (User $user, string $ability) {
            if($user->isSecretariat()){
                return true;
            }
        });

        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        JsonResource::withoutWrapping();
    }
}
