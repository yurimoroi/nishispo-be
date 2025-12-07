<?php

namespace App\Enums;
use \Spatie\Enum\Enum;

/**
 * @method static self temporarySave()
 * @method static self correctionRequest()
 * @method static self deletionRequest()
 */
final class RevisedArticleEnum extends Enum
{
    public static function temporarySave(): self
    {
        return new self(0); 
    }

    public static function correctionRequest(): self
    {
        return new self(1); 
    }

    public static function deletionRequest(): self
    {
        return new self(2); 
    }

    protected static function values(): array
    {
        return [
            'temporarySave' => 0,
            'correctionRequest' => 1,
            'deletionRequest' => 2,
        ];
    }

    protected static function labels(): array
    {
        return [
            'temporarySave' => __('revised_article_enum.temporary_save'),
            'correctionRequest' => __('revised_article_enum.correction_request'),
            "deletionRequest" => __('revised_article_enum.deletion_request'),
        ];
    }
}
