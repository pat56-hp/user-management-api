<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityResource;
use App\Services\ActivityService;

class ActivityController extends Controller
{
    public function __construct(private ActivityService $activityService){
        $this->middleware('auth:api');
    }

    public function __invoke()
    {
        $activities = $this->activityService->getAllOfAuth();
        return response()->json([
            'data' => ActivityResource::collection($activities),
            'meta' => [
                'current_page' => $activities ? $activities->currentPage() : null,
                'last_page' => $activities ? $activities->lastPage() : null,
                'per_page' => $activities ? $activities->perPage() : null,
                'total' => $activities ? $activities->total() : null,
                'first_page_url' => $activities ? $activities->url(1) : null,
                'last_page_url' => $activities ? $activities->url($activities->lastPage()) : null,
                'next_page_url' => $activities ? $activities->nextPageUrl() : null,
                'prev_page_url' => $activities ? $activities->previousPageUrl() : null,
            ],
        ]); 
    }
}
