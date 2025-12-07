<?php

namespace App\Http\Resources;

use App\Enums\TeamCollectTypeEnum;
use App\Modules\Event\Models\GroupwarePrivileges;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {

        $isShowEvent = $request->route()->getName() === 'show.event';

        return [
            'id' => $this->id,
            'name' => $this->name,
            'activity_description' => $this->activity_description,
            'member_information' => $this->member_information,
            'group_fee' => $this->group_fee,
            'collect_type' => $this->collect_type,
            'collect_span' => $isShowEvent && $this->collect_type == 1 ? 'No display' : $this->collect_span,
            'closing_date' => $this->closing_date,
            'first_estimated_number' => $this->first_estimated_number,
            'organization' => new OrganizationResource($this->whenLoaded('organization')),
            'user' =>  UserResource::collection($this->whenLoaded('teamUsers')),
            'team_invite_tokens' => TeamInviteTokenResource::collection($this->whenLoaded('teamInviteTokens')),
            'trial_privileges' => UserTrialPrivilegesResource::collection($this->whenLoaded('trialPrivileges')),
            'team_groups' => TeamGroupResource::collection($this->whenLoaded('teamGroups')),
            'team_timelines' => UserResource::collection($this->whenLoaded('teamTimeLines')),
            'event_categories' => EventCategoryResource::collection($this->whenLoaded('eventCategories')),
            'events' => EventResource::collection($this->whenLoaded('events')),
            'groupware_privileges' => GroupWarePrivilegesResource::collection($this->whenLoaded('groupwarePrivileges')),
            'collection_type' => [
                'status' => $this->collect_type,
                'label' => TeamCollectTypeEnum::from($this->collect_type)->label
            ],
            'members_count' => $this->whenCounted('teamUsers'),
            'leaders' => TeamLeaderResource::collection($this->whenLoaded('leaders'))
        ];
    }
}
