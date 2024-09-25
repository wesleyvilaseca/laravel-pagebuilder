<template>
          <template v-if="!preloader">
            <div class="publishers-event pt-5 pb-5" :class="{ loaded: !preloader }">
                <div class="container">
                    <div class="row my-4">
                        <div class="col-md-9 mb-2">
                            <div class="form-group">
                                <input type="text" v-model="filter" class="form-control form-control-lg" id="inputPassword2" placeholder="Busca por editoras" @keydown.enter.prevent="getPublishers()">
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button type="submit" class="btn btn-dark btn-lg btn-block" @click.prevent="getPublishers()">Pesquisar</button>
                        </div>
                    </div>
                    <div v-if="publishersState.publishers.data.length === 0">
                        <div class="text-center">Não há Editoras</div>
                    </div>
                    <div v-else>
                        <div class="row my-4">
                            <div class="col-lg-3 col-md-6 col-sm-6 mb-4" v-for="(publisher, index) in publishersState.publishers.data" :key="index">
                                <div class="card card-event" @click.prevent="goToPublisher(publisher)">
                                    <div class="card-body">
                                        <img class="card-img-top" :src="publisher.logo" :alt="publisher.name">
                                    </div>
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

.card-body {
    display: flex;
    align-items: center;
    justify-content: center;
}

img.card-img-top {
    max-width: 80%;
    height: auto;
    object-fit: contain;
    max-height: 120px;
}

.card-footer {
    width: 100%;
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

.card-event {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    cursor: pointer;
}

/* .img-160 {
    width: 160px;
    height: 160px;
    object-fit: cover;
    display: block;
}

.img-100 {
    width: 100px;
    height: 100px;
    object-fit: cover;
}

.img-80 {
    width: 80px;
    height: 80px;
    object-fit: cover;
}

.img-60 {
    width: 60px;
    height: 60px;
    object-fit: cover;
} */

</style>

<script>
import store from '../../../../store';
import { timer } from '../../../../supports/helper.js';
import VueSkeletonLoader from 'vue3-skeleton-loader';
import 'vue3-skeleton-loader/dist/style.css';

export default {
    name: 'EventPublishersBlockComponent',
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
            if (window.location.pathname.includes(this.event)) {
                return window.location.href = `editoras/${publisher.url}/editora`;
            }

            return window.location.href = `${this.event}/editoras/${publisher.url}/editora`;
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