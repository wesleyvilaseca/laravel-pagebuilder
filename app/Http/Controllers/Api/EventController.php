<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Services\EventService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventController extends Controller
{
    public function __construct(
        protected EventService $eventService
    )
    {
        
    }

    public function index(EventRequest $request) {
        try {
            $event = $this->eventService->getEventByUrl($request->flag);
            return new EventResource($event);
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
