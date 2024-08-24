<template>
    <template v-if="preloader">
        <div class="container-slidein">
            <VueSkeletonLoader type="text" width="100%" height="250px"/>
        </div>
    </template>
    <template v-else>
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                <img class="d-block w-100" src="https://baconipsum.com/wp-content/uploads/2014/09/any-ipsum-banner-772x250.png" alt="First slide">
                </div>
                <div class="carousel-item">
                <img class="d-block w-100" src="https://baconipsum.com/wp-content/uploads/2014/09/any-ipsum-banner-772x250.png" alt="Second slide">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div> 
    </template>

</template>
<style scoped>
.container-slidein {
    max-width: 100%;
    padding: 12px;
    border: 2px solid #0000001E;
    border-radius: 0.25rem;
  }
</style>

<script>
import { Http } from '../../../../config/axiosConfig.js';
import VueSkeletonLoader from 'vue3-skeleton-loader';
import 'vue3-skeleton-loader/dist/style.css';

export default {
    name: 'SlideinBannerEventComponent',
    props: {
        event: {
            type: String,
            default: ''
        }
    },
    components: {
        VueSkeletonLoader,
    },
    data: () => ({
        preloader: true,
        banners: []
    }),
    computed: {},
    mounted() {
        if (!this.event) {
            this.preloader = false;
            return;
        }

        this.getBanners();
    },
    created() {},
    methods: {
        async getBanners() {
            this.preloader = true;
            const params = { flag: this.event};
            try {
                const { data } = await Http.get('event-banners', { params });
                this.banners = data.data;
                this.preloader= false;
            } catch (error) {
                this.preloader = false;
                console.error(error);
            }
        },

        timer(time = 1000) {
            return new Promise((resolve) => {
                setTimeout(() => {
                    resolve('Data loaded');
                }, time);
            });
        }
    },
}
</script>