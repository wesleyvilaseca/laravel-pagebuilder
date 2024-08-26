<template>
          <template v-if="!preloader && eventState.address.instruction">
            <div class="location-event pt-5 pb-5" :class="{ loaded: !preloader }">
                <div class="top-section container">
                    <div class="title">Localização do evento </div>
                    <div class="subtitle">
                        {{ eventState.address.instruction }}
                    </div>
                </div>
                <div>
                    <div class="map-container">
                        <div v-html="eventState.address.google_iframe"></div>
                    </div>
                </div>
            </div>
          </template>

          <template v-if="preloader || !eventState.address.instruction">
            <div class="container pt-5 pb-5">
                <VueSkeletonLoader class="pb-2" type="text" width="100%" height="30px"/>
                <VueSkeletonLoader type="text" width="100%" height="20px"/>
            </div>
             <div class="container-fluid">
                <VueSkeletonLoader type="text" width="100%" height="300px"/>
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
  .location-event {
    opacity: 0;
    transition: opacity 0.5s ease;
  }

  .location-event.loaded {
    opacity: 1;
  }
  
  .location-event p a {
    color: var(--secundary);
    font-size: 20px;
  }

  .location-event .top-section .title {
    font-size: 32px;
    padding-bottom: 15px;
  }

.location-event .top-section .subtitle {
    font-size: 16px;
    padding-bottom: 15px;
  }

    .map-container {
        position: relative;
        height: 400px;
        width: 100%;
        overflow: hidden;
    }

    .map-container div {
        border: 0;
        width: 100%;
        height: 100%;
    }
</style>

<script>
import store from '../../../../store';
import { timer } from '../../../../supports/helper.js';
import VueSkeletonLoader from 'vue3-skeleton-loader';
import 'vue3-skeleton-loader/dist/style.css';

export default {
    name: 'BlockEventGoogleMapsLocationComponent',
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
        preloader: true
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
                return this.preloader = false;
            }
        }
    },
}
</script>