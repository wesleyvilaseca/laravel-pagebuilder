<?php
$id = 'ID_' . Illuminate\Support\Str::random(20);
?>

<div class="container">
    <h2 class="title text-center">Exemplo de componente VUEjs</h2>
    <h4 class="subtitle text-center mb-4">
      este elemento é feito em vue, porém, por ser um script compilado, não é possivel editar o mesmo
    </h4>

    <div id='<?= $id ?>'></div>

</div>


<script defer>
    const _event = window.uspEvent ?? event ?? '';
    var id  = '<?= $id ?>';
    window.mountMeuBlocoComponent(id, _event);
</script>