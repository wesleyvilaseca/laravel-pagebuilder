<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventBooksResource;
use App\Http\Resources\EventScheduleGalleryResource;
use App\Services\EventService;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventBooksController extends Controller
{
    public function __construct(
        protected EventService $eventService
    ) {
    }

    public function index(EventRequest $request) {
        try {
            $books = $this->eventService->getBooksEvent($request->flag, $request);
            return EventBooksResource::collection($books);
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
