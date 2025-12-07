<?php

namespace App\Modules\Weather\Services;

use App\Http\ApiResponse\ApiResponse;
use App\Http\Resources\WeatherResource;
use App\Modules\Weather\Repositories\WeatherRepository;

class WeatherService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected WeatherRepository $weatherRepository
    ) {}

    public function create(array $data)
    {
        try {

            $weather = $this->weatherRepository->create($data);

            if (!$weather) {
                return ApiResponse::error(__('weather_create_update_failed'));
            }
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success($weather);
    }

    public function weather(string $id)
    {
        try {
            $weather = $this->weatherRepository->find(
                id: $id,
                with: ['company']
            );
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success($weather);
    }

    public function update(array $data, string $id)
    {
        try {
            $weather = $this->weatherRepository->find(
                id: $id,
            ); 

            if (!$weather) {
                return ApiResponse::error(__('weather_create_update_failed'));
            }
        
            $weather->update($data);
         
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }

        return ApiResponse::success($weather);
    }

    public function getWeather()
    {
        try {

            $weather = $this->weatherRepository->all()->first();

            return ApiResponse::success(new WeatherResource($weather));
        } catch (\Throwable $th) {
            return ApiResponse::error($th->getMessage());
        }
    }
}
