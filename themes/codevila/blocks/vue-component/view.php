<?php 
$id = 'ID_' . Illuminate\Support\Str::random(20);
?>

<div class="container">
    <h2 class="title text-center">Exemplo de componente VUEjs</h2>
    <h4 class="subtitle text-center mb-4">
      este elemento é feito em vue, porém, por ser um script compilado, não é possivel editar o mesmo <?= $block->setting("element_id") ?>
    </h4>

    <div id='<?= $id ?>'></div>

</div>


<script>
    const id  = '<?= $id ?>';
    window.mountMeuBlocoComponent(id, 'teste', 'tese');
</script>