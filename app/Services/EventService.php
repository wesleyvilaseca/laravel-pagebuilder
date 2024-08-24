<?

namespace App\Services;

use App\Models\Event;
use Error;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventService {

    public function __construct(
        protected Event $eventRepository
    ) {
        
    }

    public function getEventByUrl(string $url) {
        return $this->eventRepository->where('url', $url)->first();
    }

    public function getEventBannerGalleryByEventUrl(string $url) {
        $event = $this->eventRepository->where('url', $url)->first();
        if (!$event) {
            throw new NotFoundHttpException('Event not found');
        }

        return $event->banners;
    }
 }