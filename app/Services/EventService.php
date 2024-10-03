<?php

namespace App\Services;

use App\Http\Requests\EventRequest;
use App\Models\Book;
use App\Models\Event;
use Error;
use Illuminate\Support\Facades\DB;
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
        // DB::listen(function ($query) {
        //     // Mostra a query, os bindings e o tempo de execução
        //     logger($query->sql);
        //     logger($query->bindings);
        //     logger($query->time);
        // });
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
        $query = Book::whereHas('publishers', function ($query) use ($publisherIds) {
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

        if ($request->has('price_discount')) {
            // Supondo que o desconto presencial é armazenado como um número com ponto decimal
            $query->where('price_discount', $request->price_discount);
        }

        if ($request->has('link')) {
            $query->where('link', 'like', '%' . $request->link . '%');
        }

        // Filtro pelos autores
        if ($request->has('author_name')) {
            $query->where('author', 'like', '%' . $request->author_name . '%');
        }

        // Filtro pelas editoras
        if ($request->has('publisher_name')) {
            $query->whereHas('publishers', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->publisher_name . '%');
            });
        }

        // Ordenar e paginar os resultados
        $query->orderBy($request->column ?? 'name', $request->order ?? 'asc');
        
        return $query->paginate($request->per_page ?? 10);
    }
 }