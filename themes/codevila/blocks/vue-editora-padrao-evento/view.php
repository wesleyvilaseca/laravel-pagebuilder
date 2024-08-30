<?php $id = 'ID_' . Illuminate\Support\Str::random(20); ?>

<div id='<?= $id ?>'></div>

<script>
    var _event = window.uspEvent ? window.uspEvent : event ? event : '';
    var _publisher = publisher ? publisher : '';
    var id  = '<?= $id ?>';
    window.BlockEventPublisherComponent(id, event, publisher);
</script>