<?php $id = 'ID_' . Illuminate\Support\Str::random(20); ?>

<div id='<?= $id ?>'></div>

<script>
    const _event = window.uspEvent ? window.uspEvent : event ? event : '';
    var id  = '<?= $id ?>';
    window.SlideinBannerEventComponent(id, _event);
</script>