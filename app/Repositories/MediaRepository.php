<?php

namespace App\Repositories;

use App\Models\BaseModel;
use App\Modules\Articles\Models\Article;
use App\Modules\Articles\Models\RevisedArticle;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(\Spatie\MediaLibrary\MediaCollections\Models\Media $media)
    {
       parent::__construct($media);
    }

    public function getMediaUrl($collection)
    {
        return $collection->map(function ($article) {
            $article->media_urls = $article->getMedia('images')->map(function ($media) {
                return [
                    'original' => $media->getUrl(),
                    'carousel' => $media->getUrl('carousel'),
                    'thumbnail_medium' => $media->getUrl('thumbnail-medium'),
                    'thumbnail_small' => $media->getUrl('thumbnail-small'), 
                ];
            });
            return $article;
        });
    }

    public function loadMedia($model)
    {
        return $model->getMedia('images')->map(function ($media) {
            return [
                'original' => $media->getUrl(),
                'carousel' => $media->getUrl('carousel'),
                'thumbnail_medium' => $media->getUrl('thumbnail-medium'),
                'thumbnail_small' => $media->getUrl('thumbnail-small'),
            ];
        });
    }

    public function uploadMultiple($attachments, Model $model)
    { 
        collect($attachments)->each(function($attachment) use ($model) {
            $model->addMedia($attachment)->toMediaCollection('article-attachments');
        });
    }

    public function uploadMedia(Model $model , $mediaRequestName = 'image' , $mediaCollectionName = 'images')
    {
        $model->addMediaFromRequest($mediaRequestName)->toMediaCollection($mediaCollectionName);
    }

    public function revisedArticleMedia(Article $article,RevisedArticle $revisedArticle)
    {
        $revisedArticleMedias = $revisedArticle->getMedia('article-attachments');

        if($revisedArticleMedias){
            collect($revisedArticleMedias)->each(function(Media $media) use ($article){
                $media->model_id = $article->id;
                $media->model_type = get_class($article);
                info(get_class($article));
                $media->save();
            });
        }
    }
}
