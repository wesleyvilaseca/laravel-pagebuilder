<script>
    const event = "{{ @$event ?? '' }}";
    const eventPrincipal = "{{ @$principal ?? 0 }}";
    window.uspEvent = event;
    if (!event) {
        console.warning("Não há evento selectionado");
    }
</script>

{!! $html !!}



<script>
   $(document).ready(function() {
        const link = document.getElementById('editorasLink');
        if (link) {
            link.href = eventSubDomain ? `/${eventSubDomain}/editoras` : '/editoras';
        }
    });
</script>
