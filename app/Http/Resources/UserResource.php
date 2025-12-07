<?php

namespace App\Http\Resources;

use App\Enums\GenderEnum;
use App\Enums\UserContributorStatus;
use App\Modules\Event\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $contributorStatus = UserContributorStatus::from($this->contributor_status);

        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'email' => $this->email,
            'family_name' => $this->family_name,
            'given_name' => $this->given_name,
            'full_name' => $this->family_name . " " . $this->given_name,
            'phonetic_family_name' => $this->phonetic_family_name,
            'phonetic_given_name' => $this->phonetic_given_name,
            'nickname' => $this->nickname,
            'birth_date' => $this->birth_date,
            'gender' => [
                'status' => GenderEnum::from($this->gender_type)->value,
                'label' => GenderEnum::from($this->gender_type)->label,
            ],
            'postal_code' => $this->postal_code,
            'province' => $this->province,
            'address_1' => $this->address_1,
            'address_2' => $this->address_2,
            'address_3' => $this->address_3,
            'phone_number' => $this->phone_number,
            'mobile_phone_number' => $this->mobile_phone_number,
            'login_id' => $this->login_id,
            'email_verified_at' => $this->email_verified_at,
            'password_reset_token' => $this->password_reset_token,
            'contributor_name' => $this->contributor_name,
            'rakuten_id' => $this->rakuten_id,
            'favorite_sport' => $this->favorite_sport,
            'favorite_gourmet' => $this->favorite_gourmet,
            'secretariat_flg' => $this->secretariat_flg,
            'contributor' => [
                'status' =>  $contributorStatus->value,
                'label' =>  $contributorStatus->label
            ],
            'advertiser_flg' => $this->advertiser_flg,
            'advertiser_name' => $this->advertiser_name,
            'line_id' => $this->line_id,
            'x_id' => $this->x_id,
            'facebook_id' => $this->facebook_id,
            'instagram_id' => $this->instagram_id,
            'avatar' => $this->avatar,
            'company' => new CompanyResource($this->whenLoaded('company')),
            'organizations' => OrganizationResource::collection($this->whenLoaded('organizations')),
            'affiliate' => OrganizationResource::collection($this->whenLoaded('affiliate')),
            'articles' => ArticleResource::collection($this->whenLoaded('articles')),
            'teams' => TeamResource::collection($this->whenLoaded('teams')),
            'articles_count' => [
                'published' => $this->published_count ?? 0,
                'pending' => $this->pending_count ?? 0
            ],
            'permissions' => [
                'is_secretariat' => (bool)$this->secretariat_flg,
                'is_advertiser' => (bool)$this->advertiser_flg,
                'can_contribute_article' => $contributorStatus->value === UserContributorStatus::approved()->value,
                'is_administrator_flg' => isset($this->organizationUser[0]) ? (bool)$this->organizationUser[0]['administrator_flg'] : false,
                'is_event_leader' => optional($this->pivot)->leader_flg,
                'is_general' => !$this->secretariat_flg && $contributorStatus->value === UserContributorStatus::notApplied()->value,
            ]
        ];
    }
}
