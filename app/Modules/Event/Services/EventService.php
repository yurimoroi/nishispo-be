<?php

namespace App\Modules\Event\Services;

use App\Filters\EventExpiringSoonFilter;
use App\Filters\EventOrganizationFilter;
use App\Filters\EventSearchFilter;
use App\Filters\GroupwarePrivilegesFilter;
use App\Filters\GroupwarePrivilegesUnpaidFilter;
use App\Repositories\EventRepository;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\ApiResponse\ApiResponse;
use App\Http\Resources\EventEditResource;
use App\Http\Resources\EventResource;
use App\Http\Resources\EventStoreResource;
use App\Http\Resources\PaginateResource;
use App\Modules\Event\Models\Event;
use App\Modules\Event\Models\Team;
use App\Repositories\MediaRepository;
use App\Repositories\TeamRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class EventService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected EventRepository $eventRepository,
        protected MediaRepository $mediaRepository,
        protected TeamRepository $teamRepository
    ) {}

    public function search()
    {
        try {
            $events = $this->eventRepository->all(
                with: ['team' => function($q) {
                    $q->withCount('teamUsers');
                }, 'team.organization', 'team.teamGroups', 'team.groupwarePrivileges', 'media','team.teamUsers'],            
                allowedFilters: [
                    AllowedFilter::exact('org', 'team.organization.id'),
                    AllowedFilter::custom('search', new EventSearchFilter),
                    AllowedFilter::custom('payment', new GroupwarePrivilegesFilter),
                    AllowedFilter::custom('expire', new EventExpiringSoonFilter),
                    AllowedFilter::custom('unpaid', new GroupwarePrivilegesUnpaidFilter)
                ],
                paginate: true,
                perPage: request('per_page', Event::LIMIT_PER_PAGE)
            );         
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(PaginateResource::make($events, EventResource::class));
    }

    public function event(string $eventId)
    {
        try {

            $event = $this->eventRepository->find(
                id: $eventId,
                with: ['team.organization', 'team.organization', 'team.groupwarePrivileges', 'team.leaders', 'media', 'eventCategory'],
            );

            if (!$event) {
                return ApiResponse::error("Event not found.");
            }
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(new EventResource($event));
    }


    public function store(array $data)
    {
        try {
            $results = DB::transaction(function () use ($data) {
                $team = $this->teamRepository->create([
                    "organization_id" => $data['organization_id'],
                    "name" => $data['name'] ?? null,
                    "activity_description" => $data['activity_description'] ?? null,
                    "member_information" => $data['member_information'] ?? null,
                    "group_fee" => $data['group_fee'] ?? 0,
                    "collect_type" => $data['collect_type'] ?? 'monthly',
                    "collect_span" => $data['collect_span'] ?? 30,
                    "closing_date" => $data['closing_date'] ?? null,
                    "first_estimated_number" => $data['first_estimated_number'] ?? 0,
                ]);

                if (!$team) {
                    return ApiResponse::error(__('event_create_team_failed'));
                }

                $leaders = request('event_leaders', []);

                if ($leaders) {
                    $SyncLeaders = array_map(fn($leader) => [
                        "id" => Str::ulid(),
                        "team_id" =>  $team->id,
                        "user_id" => $leader,
                        "leader_flg" => 1
                    ],  $leaders);

                    $team->teamUsers()->sync($SyncLeaders);
                }

                $user = auth()->user();

                if (!$user) {
                    return ApiResponse::error(__('event_create_user_not_found'));
                }

                $event = $this->eventRepository->create([
                    "team_id" => $team->id,
                    "user_id" => $user->id,
                    "name" => $team->name,
                    "repetiton_week" => "N/A",
                    "started_at" => Carbon::now(),
                    "ended_at" => Carbon::now(),
                    "activity_location_type" => 0,
                ]);

                if (request()->file('event_images')) {
                    $this->mediaRepository->uploadMedia(
                        model: $event,
                        mediaRequestName: 'event_images',
                        mediaCollectionName: 'event-images'
                    );
                }

                return $event;
            });
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(new EventStoreResource($results));
    }

    public function update(string $id, array $data)
    {
        try {
            $event = $this->eventRepository->find(id: $id, with: ['team']);

            if (!$event) {
                throw new Exception("Event " . __("not_found_common"));
            }

            $event->update($data);

            $event->team()->update(Arr::except($data, ['event_leaders', 'event_images']));

            $leaders = request('event_leaders');
            if ($leaders) {
                $syncLeaders = array_map(fn($leader) => [
                    "id" => Str::ulid(),
                    "team_id" => $event->team->id,
                    "user_id" => $leader,
                    "leader_flg" => 1
                ], $leaders);

                $event->team->teamUsers()->sync($syncLeaders);
            }

            if (request()->file('event_images')) {
                $event->clearMediaCollection('event-images');
                
                $this->mediaRepository->uploadMedia(
                    model: $event,
                    mediaRequestName: 'event_images',
                    mediaCollectionName: 'event-images'
                );
            }
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success(new EventStoreResource($event));
    }
}
