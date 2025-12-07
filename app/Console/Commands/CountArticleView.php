<?php

namespace App\Console\Commands;

use App\Modules\Articles\Models\ArticleViewingRank;
use App\Modules\Articles\Models\ArticleViewLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CountArticleView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:count-article-view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Count article views';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $viewCounts = ArticleViewLog::select('article_id', DB::raw('count(*) as view_count'))
            ->whereNull('processed_at')
            ->groupBy('article_id')
            ->get();

            foreach ($viewCounts as $viewCount) {
                ArticleViewingRank::updateOrCreate(
                    ['article_id' => $viewCount->article_id],
                    ['view_count' => DB::raw('view_count + ' . $viewCount->view_count)]
                );
            }

        ArticleViewLog::whereNull('processed_at')->update(['processed_at' => now()]);

        $this->info('Article view counts have been updated successfully.');
    }
}
