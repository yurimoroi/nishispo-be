<?php

namespace App\Http\Middleware;

use App\Modules\Articles\Models\Article;
use App\Modules\Articles\Models\ArticleViewLog;
use Closure;
use Illuminate\Http\Request;


class ArticleViewingLogsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $articleId = $request->id;

        if ($articleId) {
            $article = Article::find($articleId);

            if ($article) {
                $user = $request->user();
                $ipAddress = request()->header('X-Forwarded-For')
                    ? explode(',', request()->header('X-Forwarded-For'))[0]
                    : request()->ip();
                    
                $ipAddress = trim($ipAddress);

                $existingLog = ArticleViewLog::where('article_id', $article->id)
                    ->where(function ($query) use ($user, $ipAddress) {
                        if ($user) {
                            $query->where('user_id', $user->id);
                        } else {
                            $query->where('ip_address', $ipAddress);
                        }
                    })
                    ->first();

                if (!$existingLog) {
                    ArticleViewLog::create([
                        'article_id' => $article->id,
                        'user_id' => $user ? $user->id : null,
                        'ip_address' => $ipAddress,
                        'user_agent' => $request->header('User-Agent'),
                    ]);
                }
            }
        }

        return $next($request);
    }
}
