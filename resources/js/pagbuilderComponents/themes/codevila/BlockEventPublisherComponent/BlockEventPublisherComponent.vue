<template>
          <template v-if="!preloader">
            <div class="publishers-event pt-5 pb-5" :class="{ loaded: !preloader }">
                <div class="container">
                    <div>Não há Editora</div>
                    <div>
                        <div>Aleph</div>
                        <div>Fundada em 1984, a Editora Aleph foi pioneira na publicação de ficção científica no Brasil. Com sua abordagem inovadora, tornou-se referência nacional e uma das responsáveis pelo retorno do gênero às livrarias. Em 2016, fundou seu selo de não ficção, Goya, focado em clássicos da espiritualidade, psicologia comportamental e educação.</div>
                        <div class="row my-4">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        Lista de preços
                                    </div>
                                    <div class="card-body">
                                        Download
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        Site da editora
                                    </div>
                                    <div class="card-body">
                                        Download
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="social"> midia social </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </template>

          <template v-if="preloader">
            <div class="container pt-5 pb-5">
                <VueSkeletonLoader class="pb-2" type="text" width="100%" height="160px"/>
            </div>
          </template>
</template>
<style scoped>
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.5s ease-in-out;
  }

  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }
  .publisher-event {
    opacity: 0;
    transition: opacity 0.5s ease;
  }

  .publisher-event.loaded {
    opacity: 1;
  }
</style>

<script>
import store from '../../../../store';
import { timer } from '../../../../supports/helper.js';
import VueSkeletonLoader from 'vue3-skeleton-loader';
import 'vue3-skeleton-loader/dist/style.css';

export default {
    name: 'BlockEventPublishersComponent',
    props: {
        event: {
            type: String,
            default: ''
        },
        publisher: {
            type: String,
            defult: ''
        }
    },
    components: {
        VueSkeletonLoader,
    },
    data: () => ({
        preloader: true,
        publishers: [],
        btnDisabled: false,
        filter: '',
        textButton: 'Carregar mais editoras',
    }),
    setup() {
        const publishersState = store.publisher.state;
        const publishersActions =  store.publisher.actions;

        return {
            publishersState,
            publishersActions
        }
    },
    computed: {},
    mounted() {
        if (!this.publisher) {
            this.preloader = false;
            return;
        }


        // this.getPublishers();
    },
    created() {},
    methods: {
        async getPublishers() {
            this.btnLoad(true);
            const page = this.publishersState.meta.next_page;
            var params = {
                page: this.publishersState.filter == this.filter ? page : '',
                flag: this.event,
                filter: this.filter
                }
            try {
                await this.publishersActions.getPublishers(params);
                this.btnLoad(false);
            } catch (error) {
                console.error(error);
                this.btnLoad(false);
            }
        },

        goToPublisher(publisher) {
            console.log(publisher)
        },
        btnLoad(showLoadign) {
            if (showLoadign) {
                this.btnDisabled = true;
                this.textButton = '<i class="fas fa-spinner fa-spin"></i> Loading...';
            } else {
                this.btnDisabled = false;
                this.textButton = 'Carregar mais editoras'
            }
        }
    },
    watch: {
         publishersState: {
            deep: true,
            handler(newValue, oldeValue) {
                if (this.publishersState.data.length > 0) {
                    return this.preloader = false;
                }
            }
        }
    },
}
</script>