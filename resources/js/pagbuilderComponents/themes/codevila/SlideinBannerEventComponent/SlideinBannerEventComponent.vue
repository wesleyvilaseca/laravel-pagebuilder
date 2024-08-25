<template>
  <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
      <div v-if="!preloader" class="carousel-inner">
        <template v-if="banners.length > 0">
          <template v-for="(banner, index) in banners" :key="index">
            <div class="carousel-item" :class="index === 0 ? 'active' : ''">
              <img
                class="d-block w-100"
                :class="{ loaded: imageLoaded }"
                loading="lazy"
                @load="onImageLoad"
                :src="mobileScreem ? banner.image_mobile : banner.image_desktop"
                :alt="banner.name"
              />
            </div>
          </template>
        </template>
        <template v-if="eventState.name && banners.length > 1">
          <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </template>
      </div>

      <div v-if="preloader" class="carousel-inner">
        <div class="carousel-item active">
          <div class="container-slidein">
            <VueSkeletonLoader type="text" width="100%" height="350px"/>
          </div>
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

.carousel-item {
  height: 350px;
  width: 100%;
  overflow: hidden;
}

.carousel-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  position: absolute;
  top: 0;
  left: 0;
  transition: opacity 0.5s ease-in-out;
  opacity: 0;
}

.carousel-item img.loaded {
  opacity: 1;
}

@media (max-width: 768px) {
  .carousel-item {
    height: 300px;
  }

    .carousel-item img {
     object-position: 60% 100%;
  }
}

@media (max-width: 580px) {
  .carousel-item {
    max-width: 100%;
  }

  .carousel-item img {
    object-position: 70% 90%;
  }
}

@media (max-width: 400px) {
  .carousel-item {
    max-width: 100%;
  }

  .carousel-item img {
    object-position: 50% 90%;
  }
}


</style>

<script>
import store from '../../../../store';
import { timer } from '../../../../supports/helper.js';
import VueSkeletonLoader from 'vue3-skeleton-loader';
import 'vue3-skeleton-loader/dist/style.css';

export default {
    name: 'SlideinBannerEventComponent',
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
        imageLoaded: false,
        preloader: true,
        banners: [{
            image_mobile: "https://baconipsum.com/wp-content/uploads/2014/09/any-ipsum-banner-772x250.png",
            image_desktop: "https://baconipsum.com/wp-content/uploads/2014/09/any-ipsum-banner-772x250.png" 
        }],
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
        this.updateLayout();
        window.addEventListener('resize', this.updateLayout);

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
        },

        updateLayout() {
            this.mobileScreem = window.innerWidth <= 400;

            this.banners = [];
            this.banners = this.eventState.banners;
        },

        onImageLoad() {
            this.imageLoaded = true;
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