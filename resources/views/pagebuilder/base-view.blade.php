{!! $html !!}

<script>
    const event = "{{ @$event ?? '' }}";
    window.uspEvent = event;

    if (!event) {
        console.log("Não há evento selectionado");
    }
</script>