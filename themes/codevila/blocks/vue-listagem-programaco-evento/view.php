<?php $id = 'ID_' . Illuminate\Support\Str::random(20); ?>

<div id='<?= $id ?>'></div>

<script>
    var id  = '<?= $id ?>';
    var _event = window.uspEvent ? window.uspEvent : event ? event : '';
    window.ScheduleEventComponent(id, _event);
</script>