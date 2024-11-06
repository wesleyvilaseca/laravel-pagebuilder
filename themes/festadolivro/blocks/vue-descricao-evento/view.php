<?php $id = 'ID_' . Illuminate\Support\Str::random(20); ?>

<div id='<?= $id ?>'></div>

<script>
    var _event = window.uspEvent ? window.uspEvent : event ? event : '';
    var id  = '<?= $id ?>';
    window.HtmlBlockEventDescriptionComponent(id, _event);
</script>