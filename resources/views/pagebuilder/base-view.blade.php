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
</script>
