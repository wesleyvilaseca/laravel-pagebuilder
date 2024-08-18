<div class="mb-3">
    <label for="first_name" class="form-label">Primeiro nome</label>
    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ @$user->first_name ?? old('first_name') }}">
</div>

<div class="mb-3">
    <label for="last_name" class="form-label">Ultimo nome</label>
    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ @$user->last_name ?? old('last_name') }}">
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="text" class="form-control" id="email" name="email" value="{{ @$user->email ?? old('email') }}">
</div>
<div class="form-group">
  <label for="exampleFormControlSelect1">Selecione o perfil</label>
  <select class="form-control" id="exampleFormControlSelect1" name="role_id" required>
    <option disabled selected>Selecione uma opção</option>
        @foreach ($roles_list as $role)
            <option value="{{ $role->id }}" {{ $role_user == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
        @endforeach
  </select>
</div>

<div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control" id="password" name="password">
</div>

<button type="submit" class="btn btn-primary">Salvar</button>