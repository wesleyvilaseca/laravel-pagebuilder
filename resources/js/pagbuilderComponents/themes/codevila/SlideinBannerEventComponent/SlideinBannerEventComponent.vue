<template>
    <div v-if="!preloader && banners.length > 0">
      <SliderBootstrapComponent :gallery="banners" />
    </div>

    <div v-if="preloader || banners.length <= 0" class="carousel-inner">
      <div class="carousel-item active">
        <div class="container-slidein">
          <VueSkeletonLoader type="text" width="100%" height="350px"/>
        </div>
      </div>
    </div>
</template>
<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.1s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}

.container-slidein {
    max-width: 100%;
    padding: 12px;
    border: 2px solid #0000001E;
    border-radius: 0.25rem;
}

</style>

<script>
import store from '../../../../store';
import { timer } from '../../../../supports/helper.js';
import VueSkeletonLoader from 'vue3-skeleton-loader';
import 'vue3-skeleton-loader/dist/style.css';
import SliderBootstrapComponent from '../../../../components/widgets/SliderBootstrapComponent.vue';

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
        SliderBootstrapComponent
    },
    data: () => ({
        preloader: true,
        banners: [],
    }),
    setup() {
        const eventState = store.event.state;
        const eventActions =  store.event.actions;

        return {
            eventState,
            eventActions
        }
    },
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
            try {
                await this.eventActions.getEvent(this.event);
            } catch (error) {
                this.preloader = false;
                console.error(error);
            }
        }
    },
    watch: {
        eventState() {
            if (this.eventState.name) {
                this.banners = this.eventState.banners;
                return this.preloader = false;
            }
        }
    },
}
</script>