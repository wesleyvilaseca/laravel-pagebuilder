<?php

namespace App\Services;

use App\Models\AuthorBook;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\PublisherBook;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BookService {

    public function __construct(
        protected Book $repository,
        protected PublisherBook $publisherBookRepository,
        protected Publisher $publisherRepository,
        protected UploadFileService $uploadFileService,
        protected AuthorBook $authorBookRepository
    ) {
        
    }

    public function getBookByIsbnAndUrlAndPublisher(string $isbn, string $url, string $publisherId) {
        $data = [
            'url' => $url,
            'isbn' => $isbn,
            'publisher_id' => $publisherId
        ];

        $publisher = $this->publisherRepository->find($data['publisher_id']);
        if ($publisher) {
            return $publisher->books()->firstWhere([
                'url' => $data['url'],
                'isbn' => $data['isbn'],
            ]);
        }
    }

    public function store( array $data ) {
        DB::beginTransaction();
        try {
            $url = Str::slug($data['name']);
            $data['url'] = $url;
            if (isset($data['publisher_id'])) {
                $hasBookName  = $this->publisherRepository->whereHas('books', function ($query) use ($data) {
                    $query->where(['url' => $data['url'], 'isbn' => $data['isbn']]);
                })->where('id', $data['publisher_id'])->exists();

                if($hasBookName) {
                    throw new Exception('A editora já possui um livro com o nome ' . $data['name'] . ' e ISBN: ' . $data['isbn'] );
                }
            }

            $book = $this->repository->create([
                'name' => $data['name'],
                'author' => @$data['author'],
                'subject' => @$data['subject'],
                'isbn' => @$data['isbn'],
                'description' => $data['description'],
                'price' => @$data['price'],
                'price_discount' => @$data['price_discount'],
                'link' => @$data['link'],
                'url' => $url,
                'status' => $data['status'],
            ]);

            if(isset($data['publisher_id'])) {
                $this->publisherBookRepository->create(['book_id' => $book->id, 'publisher_id' => $data['publisher_id']]);
            }

            if(isset($data['file'])) {
                $storedFileSigleImage = $this->uploadFileService->upload(
                    $data['file'],
                    'books/book-' .  $book->id . '/' . $this->repository::FILE_CATEGORY_BOOK_SINGLE_IMAGE
                );

                $upload = $this->uploadFileService->store($storedFileSigleImage);

                $this->uploadFileService->storeUploadRelation([
                    'system_upload_id' => $upload->id,
                    'relation_id' => $book->id,
                    'alias_model_relation' => $this->repository::MODEL_ALIAS,
                    'alias_category' => $this->repository::FILE_CATEGORY_BOOK_SINGLE_IMAGE
                ]);
            }

            DB::commit();
            return $book;
        } catch (Exception $e) {
            DB::rollback();
            if (isset($storedFileSigleImage)) {
                $this->uploadFileService->deleteFile($storedFileSigleImage['server_file']);
            }

            throw new Exception($e->getMessage());
        }
    }

    public function update( array $data, Book $book) {
        DB::beginTransaction();
        try {
            $url = Str::slug($data['name']);
            $data['url'] = $url;
            if ($data['publisher_id'] && $data['name'] != $book->name) {
                $hasBookName  = $this->publisherRepository->whereHas('books', function ($query) use ($data) {
                    $query->where(['url' => $data['url'], 'isbn' => $data['isbn'], 'price' => $data['price']]);
                })->where('id', $data['publisher_id'])->exists();

                if($hasBookName) {
                    throw new Exception('A editora já possui um livro com o nome ' . $data['name'] . ' e ISBN: ' . $data['isbn'] . ' e price: R$ ' .$data['price'] );
                }
            }

            $publisher = $book->publishers->first();
            if($data['publisher_id'] && !$publisher) {
                $this->publisherBookRepository->create(['book_id' => $book->id, 'publisher_id' => $data['publisher_id']]);
            } else {
                if($publisher && $data['publisher_id'] != $publisher->id) {
                    $this->publisherBookRepository->where('book_id', $book->id)->update(['publisher_id' => $data['publisher_id']]);
                }
            }

            if (isset($data['file'])) {
                $image = $book->uploads()->wherePivot('alias_category', $this->repository::FILE_CATEGORY_BOOK_SINGLE_IMAGE)->first();
                if ($image) {
                    $this->uploadFileService->deleteFile('', $image);
                }

                $storedFileSigleImage = $this->uploadFileService->upload(
                    $data['file'],
                    'books/book-' . $book->id . '/' . $this->repository::FILE_CATEGORY_BOOK_SINGLE_IMAGE
                );
                $upload = $this->uploadFileService->store($storedFileSigleImage);

                $this->uploadFileService->storeUploadRelation([
                    'system_upload_id' => $upload->id,
                    'relation_id' => $book->id,
                    'alias_model_relation' => $this->repository::MODEL_ALIAS,
                    'alias_category' => $this->repository::FILE_CATEGORY_BOOK_SINGLE_IMAGE
                ]);
            }

            $form = [
                'name' => $data['name'],
                'author' => @$data['author'],
                'subject' => @$data['subject'],
                'isbn' => @$data['isbn'],
                'description' => $data['description'],
                'price' => @$data['price'],
                'price_discount' => @$data['price_discount'],
                'link' => @$data['link'],
                'url' => $url,
                'status' => $data['status']
            ];

           $book->update($form);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            if (isset($storedFileSigleImage)) {
                $this->uploadFileService->deleteFile($storedFileSigleImage['server_file']);
            }

            throw new Exception($e->getMessage());
        }
    }

    public function delete( int $id ) {
        DB::beginTransaction();
        try {
            $book = $this->repository->find($id);
            if (!$book) {
                throw new Exception('Livro não localizado');
            }

            $files = $book->uploads;

            if (!empty($files)) {
                foreach($files as $file) {
                    $this->uploadFileService->deleteFile(null, $file);
                }
            }

            $this->publisherBookRepository->where('book_id', $id)->delete();
            $this->authorBookRepository->where('book_id', $id)->delete();
            $book->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception($e->getMessage());
        }
    }    
 }