<?php

namespace App\Console\Commands;


use App\Enums\ArticleStatusEnum;
use App\Modules\Articles\Models\ArticleTagRank;
use App\Modules\Company\Models\Company;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CountArticleTag extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:count-article-tag';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a Tag count';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = Carbon::now()->subDays(30)->startOfDay();

        $companies = Company::with(['tags' => function($q) use ($date) {
            $q->withCount(['articles' => function($q) use ($date) {
                $q->where('status', ArticleStatusEnum::published()->value)
                    ->where('published_at', '>=', $date);
            }])
            ->having('articles_count', '>', 0) 
            ->orderBy('articles_count', 'desc');
        }])
        ->get();

        if($companies){
            $companies->each(function($company){
                $company->tags->each(function($tag){
                    ArticleTagRank::updateOrCreate([
                        'article_tag_id' => $tag->id,
                        'count' => $tag->articles_count
                    ]);
                });
            });
        }
        
        $this->info('Tag rankings generated successfully.');
       
    }
}
