<?php

namespace App\Modules\Weather\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\WeatherCreateRequest;
use App\Http\Requests\WeatherUpdateRequest;
use App\Modules\Weather\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function __construct(
        protected WeatherService $weatherService
        )
    {}

    public function store(WeatherCreateRequest $request)
    {
        return $this->weatherService->create($request->validated());
    }

    public function show(string $id)
    {
        return $this->weatherService->weather($id);
    }

    public function update(WeatherUpdateRequest $request, string $id)
    {
        return $this->weatherService->update($request->validated(),$id);
    }

    public function getWeather()
    {
        return $this->weatherService->getWeather();
    }
}
