<?php

namespace App\Modules\Event\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventStoreRequest;
use App\Modules\Event\Models\Event;
use App\Modules\Event\Services\EventService;
use App\Modules\User\Models\User;
use App\Repositories\EventRepository;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct(
        protected EventService $eventService
    ) {}

    public function index()
    {
        return $this->eventService->search();
    }

    public function show(string $id)
    {
        return $this->eventService->event($id);
    }

    public function store(EventStoreRequest $request)
    {
        $data = $request->validated();
        return $this->eventService->store($data);
    }

    public function update(string $id, EventStoreRequest $request)
    {
        $data = $request->validated();
        return $this->eventService->update($id, $data);
    }
}
