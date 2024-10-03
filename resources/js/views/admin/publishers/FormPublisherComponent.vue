<template>
    <form @submit.prevent="save" enctype="multipart/form-data" method="post" ref="publisherForm">
        <div class="mb-3">
            <label for="title" class="form-label">Nome da editora *</label>
            <input type="text" class="form-control form-control-sm" v-model="form.name">
        </div>

        <div class="form-group mb-3">
            <label for="description">Descrição da editora *</label>
            <textarea class="form-control" v-model="form.description" rows="3">
            </textarea>
        </div>

        <div class="card mb-3">
            <div class="row card-body">
                <div class="col-2 text-center position-relative">
                    <img 
                    :src="imageSrcLogo" 
                    class="position-relative"
                    style="max-width: 90px;"
                    >
                    <button
                        v-if="selectedFileLogo || !imageSrcLogo.includes('no-image')"
                        class="btn btn-outline-danger btn-sm btn-circle position-absolute"
                        style="top: -10px; left: 50%; z-index: 1;"
                        @click.prevent="clearLogo"
                        title="Limpar logo">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="col-9">
                    <label>Logo da editora</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input
                            type="file"
                            class="custom-file-input"
                            accept=".jpg, .jpeg, .png"
                            @change="handleFileLogo"
                            ref="fileInput"
                            > 
                            <label class="custom-file-label" for="logo">Selecione o arquivo</label>
                        </div>
                    </div>
                </div>
            </div>    
        </div>

        <div class="card mb-3">
            <div class="row card-body">
                <div class="col-2 text-center position-relative">
                    <template v-if="selectedFilePriceList || form.price_list?.server_file" >
                        <canvas
                        id="price-list-pdf-preview"
                        style="max-width: 90px;"></canvas>
                        <button
                        class="btn btn-outline-danger btn-sm btn-circle position-absolute"
                        style="top: -10px; left: 50%; z-index: 1;"
                        @click.prevent="clearPriceList()"
                        title="Limpar logo">
                            <i class="fas fa-times"></i>
                        </button>
                    </template>
                    <template v-else>
                        <img 
                        :src="`/assets/images/no-file.png`"
                        style="max-width: 90px;"
                        />
                    </template>
                </div>
                <div class="col-9">
                    <label>Lista de preços da editora:</label>
                    <div class="input-group">
                        <div class="custom-file">
                        <input
                        type="file"
                        class="custom-file-input"
                        accept=".pdf"
                        @change="handleFilePriceList"
                        ref="priceListInput"
                        > 
                            <label class="custom-file-label" for="price_list">Selecione o arquivo</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <section>
            <div class="mb-2">
                <h5>Informações de endereço</h5>
            </div>
            <div class="row">
                <div class="form-group mt-2 col-md-4">
                    <label>CEP:</label>
                    <input type="text"
                        id="zip_code"
                        class="form-control form-control-sm"
                        laceholder="CEP:"
                        @blur="buscacep()"
                        v-model="form.data.address.zip_code" />
                </div>
            
                <div class="form-group mt-2 col-md-5">
                    <label>Cidade:</label>
                    <input type="text"
                        class="form-control form-control-sm" placeholder="Cidade:"
                        v-model="form.data.address.city" readonly  />
                </div>
            
                <div class="form-group mt-2 col-md-2">
                    <label>UF:</label>
                    <input type="text"
                        class="form-control form-control-sm" placeholder="UF:"
                        v-model="form.data.address.state" readonly  />
                </div>
            
                <div class="form-group mt-2 col-md-4">
                    <label>Bairro:</label>
                    <input type="text"
                        class="form-control form-control-sm" placeholder="Bairro:"
                        v-model="form.data.address.district" readonly />
                </div>
            
                <div class="form-group mt-2 col-md-5">
                    <label>Endereço:</label>
                    <input type="text"
                        class="form-control form-control-sm" placeholder="Endereço:"
                        v-model="form.data.address.address" />
                </div>
            
                <div class="form-group mt-2 col-md-2">
                    <label>Numero:</label>
                    <input type="text" class="form-control form-control-sm"
                        placeholder="Numero:" 
                        v-model="form.data.address.number">
                </div>
            </div>
        </section>

        <hr>

        <section>
            <div class="mb-2">
                <h5>Redes sociais</h5>
            </div>
            <div class="row">
                <div class="form-group mt-2 col-md-4">
                    <label>Facebook:</label>
                    <input type="url" class="form-control form-control-sm"
                        placeholder="https://facebook.com/meu-facebook"
                        v-model="form.data.social.facebook">
                </div>
            
                <div class="form-group mt-2 col-md-4">
                    <label>Instagram:</label>
                    <input type="url" name="instagram" class="form-control form-control-sm"
                        placeholder="https://instagram.com/meu-instagram"
                        v-model="form.data.social.instagram">
                </div>
            
                <div class="form-group mt-2 col-md-4">
                    <label>Youtube:</label>
                    <input type="url" name="youtube" class="form-control form-control-sm"
                        placeholder="https://youtube.com/meu-youtube"
                        v-model="form.data.social.youtube">
                </div>
            </div>
        </section>

        <div class="mb-3">
            <label for="title" class="form-label">Site da editora *</label>
            <input type="url" class="form-control form-control-sm"
                v-model="form.site">
        </div>

        <div class="mb-3">
            <label for="title" class="form-label">Email da editora</label>
            <input type="email" class="form-control form-control-sm"
                v-model="form.email">
        </div>

        <div class="form-group">
            <label for="status">Selecione o status</label>
            <select class="form-control" v-model="form.status" required>
                <option disabled selected>Selecione uma opção</option>
                <option value="1">Ativo</option>
                <option value="0">Inativo</option>
            </select>
        </div>

        <AlertMessagesComponente
            :errors="errors"
            @update:errors="errors = $event"
        />

        <div class="text-center">
            <template v-if="loading">
                <button class="btn btn-success btn-sm disabled">
                    <i class="fas fa-spinner fa-spin"></i> 
                    Salvando ...
                </button>
            </template>
            <template v-else>
                <button class="btn btn-success btn-sm" type="submit">Salvar</button>
            </template>
        </div>
    </form>
</template>

<script>
    import { Http } from '../../../config/axiosConfig';
    import AlertMessagesComponente from '../../../components/widgets/AlertMessagesComponente.vue';
    import { toast } from 'vue3-toastify';

    export default {
        name: 'FormPublisherComponent',
        components: {
            AlertMessagesComponente,
        },
        props: {
            publisher: {
                type: Object,
                default: {
                    name: '',
                    description: '',
                    site: '',
                    email: '',
                    status: false,
                    logo: {},
                    price_list:{},
                    data: {
                        address: {
                            address: '',
                            zip_code: '',
                            address: '',
                            state: '',
                            number: '',
                            district: '',
                            city: ''
                        },
                        social: {
                            facebook: '',
                            instagram: '',
                            youtube: ''
                        }
                    }
                }
            }
        },
        data() {
            return {
                csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                errors: '',
                loading: false,
                imageSrcLogo: '',
                selectedFileLogo: '',
                selectedFilePriceList: '',
                form: { ...this.publisher }
            }
        },
        mounted() {
            if (this.publisher.id) {
                this.form = this.publisher;
            }

            if (this.form.price_list?.server_file) {
                this.previewExistingPDF(window.location.origin + '/storage/' + this.form.price_list.server_file);
            }

            this.imageSrcLogo = this.form.logo?.server_file ? '/storage/' + this.form.logo.server_file : '/assets/images/no-image.jpg';
        },
        methods: {
            handleFileLogo(event) {
                const label = event.target.nextElementSibling;
                const file = event.target.files[0];
                if (file) {
                    label.textContent = file.name; // Atualiza o label com o nome do arquivo

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imageSrcLogo = e.target.result; // Atualiza a imagem
                    };
                    reader.readAsDataURL(file); // Lê o arquivo
                    this.selectedFileLogo = file; // Armazena o arquivo
                } else {
                    label.textContent = 'Selecione o arquivo'; // Reseta o texto do label
                }
            },
            clearLogo() {
                this.imageSrcLogo = '/assets/images/no-image.jpg';
                this.$refs.fileInput.value = '';
                this.selectedFileLogo = null;
                const label = this.$refs.fileInput.nextElementSibling;
                label.textContent = 'Selecione o arquivo';

                if (this.form.logo?.server_file) {
                    this.deleteLogo();
                }
            },
            handleFilePriceList(event) {
                const file = event.target.files[0];
                if (file && file.type === "application/pdf") {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const typedArray = new Uint8Array(e.target.result);
                        this.renderPDF(typedArray);
                    };
                    reader.readAsArrayBuffer(file);
                    this.selectedFilePriceList = file;
                }
            },
            previewExistingPDF(pdfPath) {
                fetch(pdfPath)
                    .then(response => {
                        if (!response.ok) throw new Error("Falha ao carregar PDF");
                        return response.arrayBuffer();
                    })
                    .then(buffer => {
                        const typedArray = new Uint8Array(buffer);                            
                        this.renderPDF(typedArray);
                    })
                    .catch(error => {
                        console.error("Erro ao carregar o PDF:", error);
                    });
            },
            renderPDF(typedArray) {
                pdfjsLib.getDocument(typedArray).promise.then((pdf) => {
                    pdf.getPage(1).then((page) => {
                        const canvas = document.getElementById("price-list-pdf-preview");
                        const context = canvas.getContext("2d");
                        const scale = 0.5; // Ajuste o tamanho do preview conforme necessário
                        const viewport = page.getViewport({ scale });

                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        const renderContext = {
                            canvasContext: context,
                            viewport: viewport,
                        };
                        page.render(renderContext);
                    });
                });
            },
            clearPriceList() {
                const canvas = document.getElementById("price-list-pdf-preview");
                if (canvas) {
                    const context = canvas.getContext("2d");
                    context.clearRect(0, 0, canvas.width, canvas.height);
                }

                this.$refs.priceListInput.value = '';
                this.selectedFilePriceList = null;

                if (this.form.price_list?.server_file) {
                    this.deletePriceList();
                }
            },
            async save() {
                const formData = new FormData();
                formData.append('name', this.form.name);
                formData.append('description', this.form.description);
                formData.append('site', this.form.site);
                formData.append('email', this.form.email);
                formData.append('status', this.form.status);
                formData.append('zip_code', this.form.data.address.zip_code);
                formData.append('city', this.form.data.address.city);
                formData.append('state', this.form.data.address.uf);
                formData.append('district', this.form.data.address.district);
                formData.append('address', this.form.data.address.address);
                formData.append('number', this.form.data.address.number);
                formData.append('facebook', this.form.data.social.facebook);
                formData.append('instagram', this.form.data.social.instagram);
                formData.append('youtube', this.form.data.social.youtube);

                if (this.selectedFileLogo) {
                    formData.append('logo', this.selectedFileLogo);
                }

               if (this.selectedFilePriceList) {
                    formData.append('price_list', this.selectedFilePriceList);
                }

                if(this.form.id) {
                    formData.append('_method', 'put');
                }

                this.loading = true;
                this.errors = [];
              
               try {
                    let response;
                    if(this.form.id) {
                        response = await Http.post(route('publisher.update', { id: this.form.id }), formData, { headers: { 'Content-Type': 'multipart/form-data' } });

                    } else {
                        const { data } = await Http.post(route('publisher.store'), formData);
                        this.form.id = data.editora.id;
                    }
                    
                    toast.success("Editora salva com sucesso", { autoClose: 2000 });
                } catch (error) {
                    if (error.response && error.response.data.errors) {
                        this.errors = Object.values(error.response.data.errors).flat();
                    } else {
                        console.error(error);
                    }
                } finally {
                    this.loading = false;
                }
            },
            async deleteLogo(){
                try {
                    const response = await Http.get(route('publisher.delete.logo', {id: this.form.id}), {});
                    if (response.status === 200) {
                        this.form.logo.server_file = null;
                    }
                } catch (error) {
                    console.error(error);
                }
            },

            async deletePriceList() {
                try {
                    const response = await Http.get(route('publisher.delete.pricelist', {id: this.form.id}), {});
                    if (response.status === 200) {
                        this.form.price_list.server_file = null;
                    }
                } catch (error) {
                    console.error(error);
                }
            },
            buscacep() {
                const cep = this.form.data?.address?.zip_code
                if (cep.length == 9) {
                    fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.erro) {
                            alert('CEP não localizado!');
                        } else {
                            this.form.data.address.address = data.logradouro;
                            this.form.data.address.state = data.uf;
                            this.form.data.address.city = data.localidade;
                            this.form.data.address.district = data.bairro;
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao buscar o CEP:', error);
                    });
                }
            }
        },
        watch: {},
    }
</script>

<style>
.image-container {
    position: relative;
    display: inline-block;
}

.btn-circle {
    width: 18px;
    height: 18px;
    padding: 0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    position: absolute;
}

.btn-circle i {
    font-size: 10px;
}
</style>