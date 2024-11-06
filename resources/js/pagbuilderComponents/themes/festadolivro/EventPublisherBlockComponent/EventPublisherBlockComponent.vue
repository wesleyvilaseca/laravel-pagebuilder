<template>
          <template v-if="!preloader">
            <div class="publishers-event pt-5 pb-5" :class="{ loaded: !preloader }">
                <div class="container">
                    <div>
                        <section class="publisher-detail pb-5">
                            <div class="row">
                                <div class="col-12">
                                    <div class="identify">
                                        <img class="logo" :src="publisherData.logo" />
                                        <div class="name pt-2"> {{ publisherData.name }} </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        
                        <section class="publisher-description">
                            <div v-if="publisherData.description"> {{ publisherData.description }} </div>
                        </section>

                        <section class="publisher-social-media">
                            <div class="row my-4">
                                <div class="col-md-4 pb-3" v-if="publisherData.price_list">
                                    <div class="card">
                                        <div class="card-header text-center">
                                            Lista de preços
                                        </div>
                                        <div class="card-body text-center">
                                           <a :href="publisherData.price_list" target="_blank">
                                            <i class="fa-solid fa-file-pdf"></i> 
                                            Download
                                           </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 pb-3" v-if="publisherData.site">
                                    <div class="card">
                                        <div class="card-header text-center">
                                            Site da editora
                                        </div>
                                        <div class="card-body text-center">
                                            <a :href="publisherData.site" target="_blank"> {{  publisherData.site }} </a>
                                        </div>
                                    </div>
                                </div>
                                 <section class="col-md-4 align-self-center text-left social-links">
                                    <div class="title">Redes Sociais</div>
                                    <div class="social-icons">
                                        <a :href="publisherData.social.facebook" class="social-link pr-2" target="_blank" v-if="publisherData.social.facebook"><i class="fa-brands fa-facebook"></i></a>
                                        <a :href="publisherData.social.instagram" class="social-link pr-2" target="_blank" v-if="publisherData.social.instagram"><i class="fa-brands fa-instagram"></i></a>
                                        <a :href="publisherData.social.twitter" class="social-link pr-2" target="_blank" v-if="publisherData.social.twitter"><i class="fa-brands fa-twitter"></i></a>
                                        <a :href="publisherData.social.youtube" class="social-link pr-2" target="_blank" v-if="publisherData.social.youtube"><i class="fa-brands fa-youtube"></i></a>
                                        <a :href="publisherData.social.linkedin" class="social-link" target="_blank" v-if="publisherData.social.linkedin"><i class="fa-brands fa-linkedin"></i></a>
                                    </div>
                                    <a :href="mailto" class="email" v-if="publisherData.email"> {{ publisherData.email }} </a>
                                </section>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
          </template>
          <template v-if="preloader">
            <div class="container pt-5 pb-5">
                <div class="container">
                    <section class="publisher-detail pb-5">
                        <div class="row">
                            <div class="col-12">
                                <div class="identify">
                                    <VueSkeletonLoader class="pb-2" type="text" width="160px" height="160px"/>
                                </div>
                            </div>
                        </div>
                    </section>
                    
                    <section class="publisher-description">
                            <VueSkeletonLoader class="pb-2" type="text" width="100%" height="40px"/>
                    </section>

                    <section class="publisher-social-media">
                        <div class="row my-4">
                            <div class="col-md-4 pb-3">
                                <div class="card">
                                    <VueSkeletonLoader class="pb-2" type="text" width="100%" height="200px"/>
                                </div>
                            </div>
                            <div class="col-md-4 pb-3" v-if="publisherData.site">
                                <div class="card">
                                    <VueSkeletonLoader class="pb-2" type="text" width="100%" height="200px"/>
                                </div>
                            </div>
                            <section class="col-md-4 align-self-center text-left social-links">
                                <div class="title"><VueSkeletonLoader class="pb-2" type="text" width="100%" height="15px"/></div>
                                <div class="social-icons">
                                    <VueSkeletonLoader class="pb-2" type="text" width="100%" height="25px"/>
                                </div>
                                <VueSkeletonLoader class="pb-2" type="text" width="100%" height="15px"/>
                            </section>
                        </div>
                    </section>
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
    .publisher-event {
        opacity: 0;
        transition: opacity 0.5s ease;
    }

    .publisher-event.loaded {
        opacity: 1;
    }

    .publisher-detail {
        display: flex;
        align-items: center;
    }

    .publisher-detail .identify {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .publisher-detail .identify img.logo {
        max-width: 200px;
    }

    .publisher-detail .identify .name {
        font-size: 18px;
    }

    .publisher-detail .address {
        display: flex;
        align-items: center;
    }

    .publisher-social-media .social-links .title {
        font-size: 18px;
        margin-top: -10px;
    }

    .publisher-social-media .social-links .social-icons {
        padding-top: 10px;
        padding-bottom: 10px;
     }

     .publisher-social-media .social-links .social-icons a,
     .publisher-social-media a {
        color: #000;
     }

    .publisher-social-media .social-links .social-icons a i {
        font-size: 20px
     }

    .publisher-social-media .social-links a.email{
        font-size: 16px;
        color: #000;
     }

    .publisher-social-media .social-links a.email:hover{
        font-size: 16px;
        color: #000;
     }

     @media (max-width: 767.98px) {
        .social-links {
            display:flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .publisher-detail .address {
            display:flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
    }
</style>

<script>
import { computed } from 'vue';
import store from '../../../../store';
import { timer } from '../../../../supports/helper.js';
import VueSkeletonLoader from 'vue3-skeleton-loader';
import 'vue3-skeleton-loader/dist/style.css';

export default {
    name: 'EventPublisherBlockComponent',
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
        preloader: false,
        publisherData: {
        name: "Editora 2",
        description: "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book",
        url: "editora-2",
        logo: "https://festadolivro.edusp.com.br/sites/default/files/inline-images/edusp-preto_0.png",
        site: 'https://cardopar.com.br',
        email: 'https://mardey@usp.br',
        price_list: 'https://festadolivro.edusp.com.br/sites/default/files/2023-10/LISTA%20DE%20PRE%C3%87OS%20-%20Alameda%20-%20Alameda%20Casa.pdf',
        address: {
            city: "Belém",
            state: "PA",
            number: null,
            address: "Passagem H-1",
            district: "Marambaia",
            zip_code: "66623-270"
        },
        social: {
            youtube: null,
            facebook: 'https://www.facebook.com/edusp.oficial',
            instagram: 'https://www.instagram.com/edusp.oficial/'
        }
    }
    }),
    setup() {
        const publishersState = store.publisher.state;
        const publishersActions =  store.publisher.actions;

        return {
            publishersState,
            publishersActions
        }
    },
    computed: {
        mailto() {
            return `mailto:${this.publisherData.email}`;
        }
    },
    mounted() {
        if (!this.publisher) {
            this.preloader = false;
            return;
        }

        this.getPublishers();
    },
    created() {},
    methods: {
        async getPublishers() {
            this.preloader = true;
            try {
                await this.publishersActions.getPublisher({ flag: this.publisher });
            } catch (error) {
                console.error(error);
                this.preloader = false;
            }
        }
    },
    watch: {
         publishersState: {
            deep: true,
            handler(newValue, oldeValue) {
                const publisher = this.publishersState.publisher;
                if (publisher.name) {
                    this.publisherData = publisher;
                    return this.preloader = false;
                }
            }
        }
    },
}
</script>