/**
 * 
 * @param { string } inputFileId 
 * @param { string } fileUrl - route file pdf
 */
function renderPDF(inputFileId, fileUrl) {
    fetch(fileUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.arrayBuffer();
        })
        .then(arrayBuffer => {
            const typedArray = new Uint8Array(arrayBuffer);
            return pdfjsLib.getDocument(typedArray).promise;
        })
        .then(pdf => {
            return pdf.getPage(1);
        })
        .then(page => {
            const scale = 1.5;
            const viewport = page.getViewport({ scale: scale });

            const canvas = document.getElementById(`${inputFileId}-pdf-preview`);
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const context = canvas.getContext('2d');
            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };

            return page.render(renderContext).promise;
        })
        .then(() => {
            const canvasContainer = document.getElementsByClassName(`${inputFileId}-container`); 
            canvasContainer[0].style.display = 'block';
        })
        .catch(error => {
            console.error('Error rendering PDF:', error);
        });
}

/**
 * 
 * @param { InputEvent } event 
 */
function handleFileInputChange(event) {
    const file = event.target.files[0];
    const label = event.target.nextElementSibling;
    const inputId = event.target.id;

    label.textContent = file ? file.name : 'Selecione o arquivo';

    if (file) {
        const previewContainer = event.target.closest('.row').querySelector('img');

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContainer.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else if (file.type === 'application/pdf') {
            const reader = new FileReader();
            reader.onload = function(e) {
                const typedArray = new Uint8Array(e.target.result);
                pdfjsLib.getDocument(typedArray).promise.then(function(pdf) {
                    pdf.getPage(1).then(function(page) {
                        const scale = 1.5;
                        const viewport = page.getViewport({ scale: scale });
                        let canvas = document.getElementById(`${inputId}-pdf-preview`);
                        if(document.getElementById(`${inputId}-pdf-preview`)) {
                            const context = canvas.getContext('2d');
                            context.clearRect(0, 0, canvas.width, canvas.height);
                        } else {
                            canvas = document.createElement('canvas');
                            canvas.id = `${inputId}-pdf-preview`; 
                            const canvasContainer = document.getElementsByClassName(`${inputFileId}-container`); 
                            if (canvasContainer.length > 0) {
                                canvasContainer[0].appendChild(canvas);
                            }
                        }
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        const renderContext = {
                            canvasContext: canvas.getContext('2d'),
                            viewport: viewport
                        };

                        const canvasContainer = document.getElementsByClassName(`${inputFileId}-container`); 
                        canvasContainer[0].style.display = 'block';

                        page.render(renderContext).promise.then(function() {
                            previewContainer.src = canvas.toDataURL();
                        });
                    });
                });
            };
            reader.readAsArrayBuffer(file);
        }
    }
}

function handleClosePreview(event) {
    event.preventDefault();
    let inputCloseId = event.currentTarget.id;
    inputFileId = inputCloseId.replace('-close-preview', '');

    const canvasContainers = document.getElementsByClassName(`${inputFileId}-container`); 
    if (canvasContainers.length > 0) {
        canvasContainers[0].style.display = 'none';
    }

    const emptyFileContainers = document.getElementsByClassName(`${inputFileId}-empty-file`); 
    if (emptyFileContainers.length > 0) {
        emptyFileContainers[0].style.display = 'block';
    }
}

function slugify(text) {
    return text
        .toString()
        .normalize('NFD') // Remove accents
        .replace(/[\u0300-\u036f]/g, '') // Remove diacritics
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9 -]/g, '') // Remove caracteres especiais
        .replace(/\s+/g, '-') // Substituir espaços por hífens
        .replace(/-+/g, '-'); // Remover hífens múltiplos
}
