<?php

use App\Http\ApiResponse\ApiResponse;
use App\Http\Middleware\PostLimitMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        using: function () {
            Route::middleware('api')
                ->prefix('api/v1')
                ->group(base_path('routes/v1.php'));

            Route::middleware('api')
            ->prefix('api/admin/v1')
            ->group(base_path('routes/v1_admin.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'company' => \App\Http\Middleware\SetCompanyMiddleware::class,
            'article.view.logs' => \App\Http\Middleware\ArticleViewingLogsMiddleware::class,
            'custom.throttle' => \App\Http\Middleware\CustomThrottleRequests::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'contributor.traning.access' => \App\Http\Middleware\ContributorTrainingAccessMiddleware::class,
            'company.post.limit' => PostLimitMiddleware::class
        ]);
        
        $middleware->trustProxies(at:'*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (HttpException $e) {
           return ApiResponse::error($e->getMessage());
        });
    })->create();
