<script>
    const event = "{{ @$event ?? '' }}";
    window.uspEvent = event;
    if (!event) {
        console.warning("Não há evento selectionado");
    }
</script>

{!! $html !!}