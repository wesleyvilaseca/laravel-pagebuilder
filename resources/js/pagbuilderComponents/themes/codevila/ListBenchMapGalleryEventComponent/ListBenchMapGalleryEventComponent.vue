<template>
      <div class="container pt-5 pb-5">
          <template v-if="!preloader">
            <template v-if="gallery.length > 0">
                <div class="text-center bench-map-gallery">
                    <template v-for="(image, index) in gallery" :key="index">
                        <img
                        :class="{ loaded: imageLoaded }"
                        loading="lazy"
                        @load="onImageLoad"
                        :src="image.image"
                        class="rounded"
                        :alt="image.name">
                    </template>
                </div>
            </template>
          </template>

          <template v-if="preloader || gallery.length <= 0">
            <div class="row">
                <VueSkeletonLoader class="pb-2" type="text" width="100%" height="400px"/>
              </div>
          </template>
      </div>
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

.bench-map-gallery img {
    width: 100%;
    transition: opacity 0.5s ease-in-out;
    opacity: 0;
}

.bench-map-gallery img.loaded {
    opacity: 1;
}
</style>

<script>
import store from '../../../../store';
import { timer } from '../../../../supports/helper.js';
import VueSkeletonLoader from 'vue3-skeleton-loader';
import 'vue3-skeleton-loader/dist/style.css';

export default {
    name: 'HtmlBlockEventDescriptionComponent',
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
        imageLoaded: false,
        gallery: []
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
        this.getDescription();
    },
    created() {},
    methods: {
        async getDescription() {
            this.preloader = true;
            try {
                if (!this.eventState.name) {
                    await this.eventActions.getEvent(this.event);
                }
            } catch (error) {
                this.preloader = false;
                console.error(error);
            }
        },

        onImageLoad() {
            this.imageLoaded = true;
        }
    },
    watch: {
        async eventState() {
            if (this.eventState.name) {
                this.gallery = this.eventState.benchMapGallery;
                return this.preloader = false;
            }
        }
    },
}
</script>