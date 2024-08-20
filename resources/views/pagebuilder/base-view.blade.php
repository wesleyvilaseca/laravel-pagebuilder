{!! $html !!}

<script>
    const event = '{{ @$event }}';
    if(event) {
        return window.upsEvent = event;
    } else {
        if (!window.event) {
            window.upsEvent = 'demo-event';
        }
    }
</script>