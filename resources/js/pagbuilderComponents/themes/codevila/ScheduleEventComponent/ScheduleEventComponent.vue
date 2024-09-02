<template>
      <div class="container pt-5 pb-5">
          <template v-if="!preloader">
            <template v-if="gallery.length > 0">
                <div class="text-center schedule-gallery">
                    <template v-for="(image, index) in gallery" :key="index">
                        <img
                        loading="lazy"
                        :src="image.image"
                        class="rounded pb-5"
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

.schedule-gallery img {
    width: 100%;
    transition: opacity 0.5s ease-in-out;
}

</style>

<script>
import store from '../../../../store';
import { timer } from '../../../../supports/helper.js';
import VueSkeletonLoader from 'vue3-skeleton-loader';
import 'vue3-skeleton-loader/dist/style.css';
import { Http } from '../../../../config/axiosConfig.js';

export default {
    name: 'ScheduleEventComponent',
    props: {
        event: {
            type: String,
            default: ''
        },
        listType: {
            type: String,
            default: 'list'
        }
    },
    components: {
        VueSkeletonLoader,
    },
    data: () => ({
        preloader: true,
        gallery: [],
    }),
    setup() {},
    computed: {},
    mounted() {
        if (!this.event) {
            this.preloader = false;
            return;
        }
        this.getSchedulesGallery();
    },
    created() {},
    methods: {
        async getSchedulesGallery() {
           try {
            this.preloader = true;
            const params = { flag: this.event };
            const { data } = await Http.get('event-schedules', { params });
            this.gallery = data.data;
            this.preloader = false;
           } catch (error) {
            this.preloader = false;
            console.log(error)
           }
        }
    },
    watch: {},
}
</script>