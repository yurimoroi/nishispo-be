<?php

namespace App\Enums;
use \Spatie\Enum\Enum;

/**
 * @method static self draft()
 * @method static self applyingPublish()
 * @method static self remand()
 * @method static self applyingRemand()
 * @method static self requestEdit()
 * @method static self requestDelete()
 * @method static self published()
 */
final class ArticleStatusEnum extends Enum
{
    public static function draft(): self
    {
        return new self(0); //In-Creation
    }

    public static function applyingPublish(): self
    {
        return new self(1); 
    }

    public static function remand(): self
    {
        return new self(2); // Returned
    }

    public static function applyingRemand(): self
    {
        return new self(3); 
    }

    public static function requestEdit(): self
    {
        return new self(4);
    }

    public static function requestDelete(): self
    {
        return new self(5); 
    }

    public static function published(): self 
    {
        return new self(6); 
    }

    protected static function values(): array
    {
        return [
            'draft' => 0,
            'applyingPublish' => 1,
            'remand' => 2,
            'applyingRemand' => 3,
            'requestEdit' => 4,
            'requestDelete' => 5,
            'published' => 6,
        ];
    }

    protected static function labels(): array
    {
        return [
            'draft' => __('article_status_enum.draft'),
            'applyingPublish' => __('article_status_enum.applying_publish'),    
            "remand" => __('article_status_enum.remand'),
            "applyingRemand" => __('article_status_enum.applying_remand_revise'),
            "requestEdit" => __('article_status_enum.request_edit'),
            "requestDelete" => __('article_status_enum.request_delete'),
            "published" => __('article_status_enum.published')
        ];
    }

    public static function getAll(): array
    {
        $values = static::values();
        $labels = static::labels();

        return array_map(function ($key, $value) use ($labels) {
            return [
                'id' => $value,
                'label' => $labels[$key] ?? null,
            ];
        }, array_keys($values), array_values($values));
    }
}
