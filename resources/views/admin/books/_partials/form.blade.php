
@php
    use Illuminate\Support\Str;
@endphp
{{-- <div class="card mb-3">
    <div class="row card-body">
        <div class="col-2 text-center">
            <img src="{{ @$image->server_file ?  asset('storage/' . $image->server_file) : asset('assets/images/no-image.jpg')}}" alt="{{ @$book->name ?? ''  }}" style="max-width: 90px;" />
           @if (@$image->server_file)
                <div class="mt-1">
                    <small>{{ Str::limit($image->original_file, 10, '...') }}</small>
                </div>
           @endif
        </div>
        <div class="col-9">
            <div class="input-group">
                <label>Foto do livro:</labe>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="image" name="image"> 
                  <label class="custom-file-label" for="image">Selecione o arquivo</label>
                </div>
              </div>
        </div>
    </div>    
</div> --}}

<div class="mb-3">
    <label for="title" class="form-label">Titulo do livro *</label>
    <input type="text" class="form-control form-control-sm" id="name" name="name"
        value="{{ @$book->name ?? old('name') }}">
</div>

<div class="form-group mb-3">
    <label for="author">Autor(es), organizador(es)</label>
    <input type="text" class="form-control form-control-sm" id="author" name="author"
        value="{{ @$book->author ?? old('author') }}">
</div>

<div class="form-group mb-3">
    <label for="subject">Assunto</label>
    <input type="text" class="form-control form-control-sm" id="subject" name="subject"
        value="{{ @$book->subject ?? old('subject') }}">
</div>

<div class="form-group mb-3">
    <label for="subject">ISBN</label>
    <input type="text" class="form-control form-control-sm" id="isbn" name="isbn"
        value="{{ @$book->isbn ?? old('isbn') }}">
</div>

<div class="form-group mb-3">
    <label for="description">Descrição do livro</label>
    <textarea class="form-control" id="description" rows="3" name="description">
        {{ @$book->description ??  old('description')  }}
    </textarea>
</div>

<div class="form-group mt-2">
    <label>Preço capa:</label>
    <input type="number" name="price" class="form-control form-control-sm" placeholder="100" step="0.01" min="0"
        value="{{ $book->price ?? old('price') }}">
</div>

<div class="form-group mt-2">
    <label>Preço com desconto:</label>
    <input type="number" name="price_discount" class="form-control form-control-sm" placeholder="15.5" step="0.01" min="0"
        value="{{ $book->price_discount ?? old('price_discount') }}">
</div>

<div class="form-group mb-3">
    <label for="subject">Link</label>
    <input type="url" class="form-control form-control-sm" id="link" name="link"
        value="{{ @$book->link ?? old('link') }}">
</div>

@if (!empty($publishers))
    <div class="form-group">
        <label for="publisher_id">Selecione a editora do livro</label>
        <select class="form-control" id="publisher_id" name="publisher_id">
            <option disabled selected>Selecione uma opção</option>
            @foreach ($publishers as $publisher)
                    <option value="{{ $publisher->id }}" {{ @$publisherBook->id == $publisher->id || @old('publisher_id') == $publisher->id ? 'selected' : '' }}>{{ $publisher->name }}</option>
                @endforeach
        </select>
    </div>
@endif

<div class="form-group">
    <label for="status">Selecione o status</label>
    <select class="form-control" id="status" name="status" required>
        <option disabled selected>Selecione uma opção</option>
        <option value="1" {{ @$book->status == 1 ? 'selected' : '' }}>Ativo</option>
        <option value="0" {{ @$book->status == 0 ? 'selected' : '' }}>Inativo</option>
    </select>
</div>

<div class="text-center">
    <button type="submit" id="bookButtonSubmit" class="btn btn-{{ @$show ? 'danger' : 'success' }} btn-sm">{{ @$show ? 'APAGAR LIVRO ' . @$book->name . ' ?' : 'Salvar'}}</button>
</div>

<style>
    .custom-file-input ~ .custom-file-label::after {
        content: "Selecionar";
    }
</style>

@section('js')
    <script>
    </script>
@stop