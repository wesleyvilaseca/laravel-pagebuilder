<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Requests\PublisherRequest;
use App\Http\Resources\PublisherResource;
use App\Services\PublisherService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PublisherController extends Controller
{
    public function __construct(
        protected PublisherService $publisherService
    )
    { 
    }

    public function index(EventRequest $request) {
        try {
            $publishers = $this->publisherService->getPublishersByEventUrl($request->flag, $request->filter ?? '');
            return PublisherResource::collection($publishers);
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

    public function getPublisher(PublisherRequest $request) {
        try {
            $publishers = $this->publisherService->getPublisherByUrl($request->flag);
            return new PublisherResource ($publishers);
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
