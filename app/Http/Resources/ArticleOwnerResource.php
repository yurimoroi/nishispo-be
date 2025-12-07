<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleOwnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            'family_name' => $this->family_name,
            'given_name' => $this->given_name,
            'phonetic_family_name' => $this->phonetic_family_name,
            'phonetic_given_name' => $this->phonetic_given_name,
            'nickname' => $this->nickname,
            'contributor_name' => $this->contributor_name,
            'advertiser_flg' => $this->advertiser_flg,
            'advertiser_name' => $this->advertiser_name
        ];
    }
}
