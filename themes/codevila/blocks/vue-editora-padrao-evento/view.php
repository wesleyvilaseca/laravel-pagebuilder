<?php $id = 'ID_' . Illuminate\Support\Str::random(20); ?>

<div id='<?= $id ?>'></div>

<script>
    var _event = window.uspEvent ? window.uspEvent : event ? event : '';
    var _publisher = typeof publisher !== 'undefined' ? publisher : '';
    var id  = '<?= $id ?>';
    window.EventPublisherBlockComponent(id, event, _publisher);(id, _event);
</script>