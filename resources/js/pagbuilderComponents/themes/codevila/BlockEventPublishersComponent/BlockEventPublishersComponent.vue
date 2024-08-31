<template>
          <template v-if="!preloader">
            <div class="publishers-event pt-5 pb-5" :class="{ loaded: !preloader }">
                <div class="container">
                    <div v-if="publishersState.publishers.data.length === 0">Não há Editoras</div>
                    <div v-else>
                        <div class="row my-4">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <input type="text" v-model="filter" class="form-control form-control-lg" id="inputPassword2" placeholder="Busca por editoras">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-dark btn-lg btn-block" @click.prevent="getPublishers()">Pesquisar</button>
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-lg-3 col-md-6 col-sm-12 mb-4" v-for="(publisher, index) in publishersState.publishers.data" :key="index">
                                <div class="card card-event">
                                    <img class="card-img-top" :src="publisher.logo" :alt="publisher.name">
                                    <div class="card-footer" @click.prevent="goToPublisher(publisher)">
                                    <span> {{ publisher.name }} </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center d-grid gap-2 d-md-block" v-if="publishersState.publishers.meta.next_page">
                        <button :style="btnStyles" type="button" class="btn btn-dark" @click.prevent="getPublishers()"
                            v-html="textButton" :disabled="btnDisabled">
                        </button>
                    </div>
                </div>
            </div>
          </template>

          <template v-if="preloader">
            <div class="container pt-5 pb-5">
                <div class="row my-4">
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                        <VueSkeletonLoader class="pb-2" type="text" width="100%" height="160px"/>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                        <VueSkeletonLoader class="pb-2" type="text" width="100%" height="160px"/>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                        <VueSkeletonLoader class="pb-2" type="text" width="100%" height="160px"/>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                        <VueSkeletonLoader class="pb-2" type="text" width="100%" height="160px"/>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                        <VueSkeletonLoader class="pb-2" type="text" width="100%" height="160px"/>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                        <VueSkeletonLoader class="pb-2" type="text" width="100%" height="160px"/>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                        <VueSkeletonLoader class="pb-2" type="text" width="100%" height="160px"/>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                        <VueSkeletonLoader class="pb-2" type="text" width="100%" height="160px"/>
                    </div>
                </div>
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
  .publishers-event {
    opacity: 0;
    transition: opacity 0.5s ease;
  }

  .publishers-event.loaded {
    opacity: 1;
  }


.load_more_btn {
border-radius: 25px;
}


.load_more_btn:hover {
background: #4060ff;
}

.load_more_btn:focus {
box-shadow: none;
outline: 0px;
}

.card-footer {
    text-transform: capitalize;
    background-color: #000;
    color: #fff;
    text-align: center;
    padding: 1rem;
    cursor: pointer;
    transition: background-color .3s ease;
}

.card-footer:hover {
    background-color: #343a40;
}

.btn-dark {
    background-color: #000;
}

.btn-dark:hover {
    background-color: #343a40;
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
            mobileScreem: false,
            type: String,
            default: ''
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
        if (!this.event) {
            this.preloader = false;
            return;
        }
        this.getPublishers();
    },
    created() {},
    methods: {
        async getPublishers() {
            this.btnLoad(true);
            const page = this.publishersState.publishers.meta.next_page;
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
            return window.location.href = `editoras/${publisher.url}/editora`;
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
                if (this.publishersState.publishers.data.length > 0) {
                    return this.preloader = false;
                }
            }
        }
    },
}
</script>