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


{{-- 
<script>
// Função para atualizar os hrefs dos links
function updateLinks() {
    // Obter o caminho atual da URL
    var currentPath = window.location.pathname;

    // Selecionar todos os elementos <a> na página
    var anchors = document.getElementsByTagName("a");

    // Iterar sobre todos os elementos <a>
    for (var i = 0; i < anchors.length; i++) {
        // Obter o href atual do link
        var href = anchors[i].getAttribute('href');

        // Verificar se o href não está vazio e se é uma URL relativa
        if (href && !href.startsWith('http') && !href.startsWith('#') && !href.startsWith('/')) {
            // Construir o novo href com o caminho atual
            var newHref = currentPath + '/' + href;

            // Garantir que não tenha barras duplicadas
            newHref = newHref.replace(/\/{2,}/g, '/');

            // Atualizar o href do link
            anchors[i].setAttribute('href', newHref);
        }
    }
}

// Chamar a função ao carregar a página
window.onload = updateLinks;
</script> --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // // Obtenha a URL atual
        const currentUrl = window.location.href;

        // Defina o valor dinâmico de `event`
        var sub = event; // Altere isso conforme necessário

        // Verifique se a URL tem exatamente dois parâmetros e o segundo parâmetro é igual ao `event`
        const regex = new RegExp(`^https?://[^/]+/${sub}(?!/)$`);
        if (regex.test(currentUrl)) {
            // Se for igual, adicione um evento de clique a todos os links
            document.querySelectorAll('a[href]').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault(); // Evite o comportamento padrão do link
                    
                    // Redirecione para a nova URL com o caminho adicional
                    const newUrl = `${currentUrl}/${link.getAttribute('href')}`;
                    window.location.href = newUrl;
                });
            });
        }


        // Adicione um evento de clique a todos os links
        document.querySelectorAll('a[href]').forEach(function(link) {
            link.addEventListener('click', function(e) {
                const linkHref = link.getAttribute('href');
                if (linkHref === 'inicio') {
                    e.preventDefault(); 
                    const urlPath = window.location.pathname.split('/').filter(Boolean);
                    const secondParam = urlPath[0]; 
                    if (secondParam === sub && eventPrincipal == false) {
                        const newUrl = `${window.location.origin}/${sub}/inicio`;
                        return window.location.href = newUrl;
                    } else {
                        const newUrl = `${window.location.origin}/inicio`;
                        return window.location.href = newUrl;
                    }
                }

                // Verifique se a URL atual já contém o caminho do href
                if (currentUrl.includes(`/${linkHref}`)) {
                    e.preventDefault(); // Evite o comportamento padrão do link

                    // Redirecione para a URL até o ponto em que o caminho do href aparece
                    const newUrl = currentUrl.split(`/${linkHref}`)[0] + `/${linkHref}`;
                    window.location.href = newUrl;
                }
            });
        });
    });
</script>