<?php

namespace App\Http\Resources;

use App\Enums\OrganizationUserStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo' => $this->logo,
            $this->mergeWhen($request->user() && $request->user()->isSecretariat(), [
                'representative_name' => $this->representative_name,
                'tel_number' => $this->tel_number,
                'email' => $this->email,
                'activity_description' => $this->activity_description,
                'birthyear_viewing_flg' => $this->birthyear_viewing_flg,
                'birthdate_viewing_flg' => $this->birthdate_viewing_flg,
                'postal_code_viewing_flg' => $this->postal_code_viewing_flg,
                'address_viewing_flg' => $this->address_viewing_flg,
                'phone_number_viewing_flg' => $this->phone_number_viewing_flg,
                'mobile_phone_number_viewing_flg' => $this->mobile_phone_number_viewing_flg,
                'email_viewing_flg' => $this->email_viewing_flg,
                'user_administrators' => SimpleUserList::collection($this->whenLoaded('adminUsers'))
            ]),
            $this->mergeWhen(
                $request->route()->getName() != 'admin.organizations.create' ||
                    $request->route()->getName() != 'admin.organizations.update' ||
                    $request->route()->getName() != 'public.article',[

                    'is_administrator_flg' => (bool)$this->pivot?->administrator_flg ?? 'n/a',
                    
                    $this->mergeWhen($this->pivot, function () {
                        return [
                            'status' =>  [
                                'status' => OrganizationUserStatus::from($this->pivot->status)->value,
                                'label' => OrganizationUserStatus::from($this->pivot->status)->label
                            ] 
                        ];
                    }),

                    'btns' => [
                        'waitingApproval' => $this->pivot?->status === OrganizationUserStatus::applyingForMembership()->value,
                        'withdraw' => $this->pivot?->status === OrganizationUserStatus::afilliation()->value,
                        $this->mergeWhen($request->user()?->isSecretariat(), [
                            'approvedWithdraw' => $this->pivot?->status === OrganizationUserStatus::applyingForWithdrawal()->value,
                        ])
                    ],

                ]
            )
        ];
    }
}
