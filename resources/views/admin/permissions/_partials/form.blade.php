<div class="mb-3">
    <label for="title" class="form-label">Nome</label>
    <input type="text" class="form-control" id="title" name="name" value="{{ @$permission ? $permission->name : '' }}">
  </div>

  <div class="mb-3">
      <label for="exampleFormControlTextarea1" class="form-label">alias</label>
      <input type="text" class="form-control" id="title" name="label" value="{{ @$permission ? $permission->label : '' }}">
    </div>
  <button type="submit" class="btn btn-primary">Salvar</button>