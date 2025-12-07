<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'contributor_waiting_approval' => $this->contributor_waiting_approval,
            'article_waiting_approval' => $this->article_waiting_approval,
            'article_waiting_edit_approval' => $this->article_waiting_edit_approval,
            'article_waiting_delete_approval' => $this->article_waiting_delete_approval,
            'article_with_revision' => $this->article_with_revision,
            'event_participation_approval' => 0,
            'withdrawal_request_pending_approval' => 0,
            'upgrade_approval' => 0,
            'downgrade' => 0,
            'club_payment_type_count' => 0,
            'club_usage_rights' => 0
        ];
    }
}
