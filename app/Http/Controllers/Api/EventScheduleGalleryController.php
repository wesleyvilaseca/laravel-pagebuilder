<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventScheduleGalleryResource;
use App\Services\EventService;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventScheduleGalleryController extends Controller
{
    public function __construct(
        protected EventService $eventService
    ) {
    }

    public function index(EventRequest $request) {
        try {
            $banners = $this->eventService->getEventScheduleGalleryByEventUrl($request->flag);
            return EventScheduleGalleryResource::collection($banners);
        } catch (NotFoundHttpException $e) {
            return response()->json([
                'error' => 'Event not found',
                'message' => $e->getMessage()
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
