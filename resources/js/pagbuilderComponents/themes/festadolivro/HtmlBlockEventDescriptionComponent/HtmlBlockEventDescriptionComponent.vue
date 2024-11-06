<template>
      <div class="container pt-5 pb-5">
          <template v-if="!preloader">
            <div class="description-event" :class="{ loaded: !preloader }">
              <div v-html="description"></div>
            </div>
          </template>

          <template v-if="preloader || !this.eventState.description">
            <div class="row">
                <div class="col-md-6">
                    <VueSkeletonLoader class="pb-2" type="text" width="100%" height="100px"/>
                    <VueSkeletonLoader type="text" width="100%" height="200px"/>
                </div>
                <div class="col-md-6">
                    <VueSkeletonLoader class="pb-2" type="text" width="100%" height="200px"/>
                    <VueSkeletonLoader type="text" width="100%" height="100px"/>
                </div>
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
  .description-event {
    opacity: 0;
    transition: opacity 0.5s ease;
  }

  .description-event.loaded {
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
        description: ''
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
    },
    watch: {
        async eventState() {
            if (this.eventState.name) {
                this.description = this.eventState.description;
                return this.preloader = false;
            }
        }
    },
}
</script>