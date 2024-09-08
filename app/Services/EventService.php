<?php

namespace App\Services;

use App\Http\Requests\EventRequest;
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
        if (!$event) {
            throw new NotFoundHttpException('Event not found');
        }

        return $event;
    }

    public function getEventBannerGalleryByEventUrl(string $url) {
        $event = $this->eventRepository->where('url', $url)->first();
        if (!$event) {
            throw new NotFoundHttpException('Event not found');
        }

        return $event->banners;
    }

    public function getEventScheduleGalleryByEventUrl(string $url) {
        $event = $this->eventRepository->where('url', $url)->first();
        if (!$event) {
            throw new NotFoundHttpException('Event not found');
        }

        return $event->schedules;
    }

    public function getBooksEvent(string $url, EventRequest $request) {
        // dd($request->all());
                // Obter o evento pelo URL
        $event = $this->eventRepository->where('url', $url)->first();
        if (!$event) {
            throw new NotFoundHttpException('Event not found');
        }

        // Verificar se há publishers associados ao evento
        $publisherIds = $event->publishers()
                            ->where('status', 1)
                            ->pluck('publishers.id'); // Especificar a tabela para evitar ambiguidade

        if ($publisherIds->isEmpty()) {
            return [];
        }

        // Construir a consulta dos livros usando a tabela pivot
        $query = \App\Models\Book::whereHas('publishers', function ($query) use ($publisherIds) {
            $query->whereIn('publishers.id', $publisherIds); // Especificar a tabela para evitar ambiguidade
        })->where('status', 1);

        // Aplicar filtros opcionais se fornecidos no request
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('subject')) {
            $query->where('subject', 'like', '%' . $request->subject . '%');
        }

        if ($request->has('isbn')) {
            $query->where('isbn', $request->isbn);
        }

        if ($request->has('price')) {
            // Supondo que o preço é armazenado como um número com ponto decimal
            $query->where('price', $request->price);
        }

        if ($request->has('presential_discount')) {
            // Supondo que o desconto presencial é armazenado como um número com ponto decimal
            $query->where('presential_discount', $request->presential_discount);
        }

        if ($request->has('virtual_discount')) {
            // Supondo que o desconto virtual é armazenado como um número com ponto decimal
            $query->where('virtual_discount', $request->virtual_discount);
        }

        if ($request->has('link')) {
            $query->where('link', 'like', '%' . $request->link . '%');
        }

        if ($request->has('url')) {
            $query->where('url', $request->url);
        }

        // Filtro pelos autores
        if ($request->has('author_name')) {
            $query->whereHas('authors', function ($query) use ($request) {
                $query->where('first_name', 'like', '%' . $request->author_name . '%');
            });
        }

        // Filtro pelas editoras
        if ($request->has('publisher_name')) {
            $query->whereHas('publishers', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->publisher_name . '%');
            });
        }

        // Ordenar e paginar os resultados
        $query->orderBy($request->column ?? 'name', $request->order ?? 'asc');
        
        return $query->paginate($request->per_page ?? 15);
    }
 }