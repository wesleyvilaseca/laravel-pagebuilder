<?php $id = 'ID_' . Illuminate\Support\Str::random(20); ?>

<div id='<?= $id ?>'></div>

<script>
    $(document).ready(function() {
        var _event = window.uspEvent ? window.uspEvent : event ? event : '';
        var id  = '<?= $id ?>';
        window.BlockEventPublishersComponent(id, _event);
    });
</script>