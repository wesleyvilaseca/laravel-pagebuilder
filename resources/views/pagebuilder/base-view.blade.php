<script>
    const event = "{{ @$event ?? '' }}";
    const eventPrincipal = "{{ @$principal ?? 0 }}";
    const publisher = "{{ @$publisher ?? '' }}"
    window.uspEvent = event;
    if (!event) {
        console.warning("Não há evento selectionado");
    }
</script>

{!! $html !!}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentUrl = window.location.href;
        var sub = event;
        document.querySelectorAll('a[href]').forEach(function(link) {
            link.addEventListener('click', function(e) {
                const linkHref = link.getAttribute('href');
                const urlPath = window.location.pathname.split('/').filter(Boolean);
                const secondParam = urlPath[0]; 

                if (linkHref === 'inicio') {
                    e.preventDefault();
                    if (secondParam === sub && eventPrincipal == false) {
                        const newUrl = `${window.location.origin}/${sub}/inicio`;
                        return window.location.href = newUrl;
                    } else {
                        const newUrl = `${window.location.origin}/inicio`;
                        return window.location.href = newUrl;
                    }
                } else if(linkHref != 'editora') {
                    e.preventDefault();
                    const newUrl = `${window.location.origin}/${sub}/${linkHref}`;
                    return window.location.href = newUrl;
                }
            });
        });
    });
</script>