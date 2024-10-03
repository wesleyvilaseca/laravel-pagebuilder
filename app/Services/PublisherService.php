<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Publisher;
use Error;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PublisherService {

    public function __construct(
        protected Publisher $publisherRepository,
        protected EventService $eventService
    ) {
        
    }

    public function getPublisherByUrl(string $url) {
        $publisher = $this->publisherRepository->where('url', $url)->first();
        if (!$publisher) {
            throw new NotFoundHttpException('Event not found');
        }

        return $publisher;
    }

    public function getPublishersByEventUrl(string $eventUrl, string $filter = null) {
        $event = $this->eventService->getEventByUrl($eventUrl);
        if (!$event) {
            throw new NotFoundHttpException('Event not found');
        }

        $query = $event->publishers()->where('status', 1);
        if ($filter) {
            $query->where('name', 'LIKE', "%$filter%");
        }
        
        return $query->orderBy('name', 'asc')->paginate(40);
    }
 }