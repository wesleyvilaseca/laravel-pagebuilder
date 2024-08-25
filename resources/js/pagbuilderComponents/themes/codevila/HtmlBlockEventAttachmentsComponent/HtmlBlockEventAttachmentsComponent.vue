<template>
      <div class="container">
          <template v-if="!preloader">
            <div class="attatchments-event" :class="{ loaded: !preloader }">
                <template v-if="attachmentsColTwo.length > 0">
                    <div class="row">
                        <div class="col-md-6">
                            <template v-for="(attachmentOne, index) in attachmentsColOne" :key="index">
                                <p>
                                    <a :href="attachmentOne.link" target="_blank"> {{ attachmentOne.name }} </a>
                                </p>
                            </template>
                        </div>
                        <div class="col-md-6">
                            <template v-for="(attachmentTwo, index) in attachmentsColTwo" :key="index">
                                <p> 
                                    <i class="fas fa-paperclip"></i>
                                    <a :href="attachmentTwo.link" target="_blank"> {{ attachmentTwo.name }} </a>
                                </p>
                            </template>
                        </div>
                    </div>
                </template>
                 <template v-else>
                     <template v-for="(attachmentOne, index) in attachmentsColOne" :key="index">
                        <p> 
                            <a :href="attachmentOne.link" target="_blank"> {{ attachmentOne.name }} </a>
                        </p>
                    </template>
                 </template>
            </div>
          </template>

          <template v-if="preloader || this.eventState.attachments.length <= 0">
            <div class="row">
                <div class="col-md-6">
                    <VueSkeletonLoader class="pb-2" type="text" width="100%" height="100px"/>
                </div>
                <div class="col-md-6">
                    <VueSkeletonLoader class="pb-2" type="text" width="100%" height="100px"/>
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
  .attatchments-event {
    opacity: 0;
    transition: opacity 0.5s ease;
  }

  .attatchments-event.loaded {
    opacity: 1;
  }
  
  .attatchments-event p a {
    color: var(--secundary);
    font-size: 20px;
  }

</style>

<script>
import store from '../../../../store';
import { timer } from '../../../../supports/helper.js';
import VueSkeletonLoader from 'vue3-skeleton-loader';
import 'vue3-skeleton-loader/dist/style.css';

export default {
    name: 'HtmlBlockEventAttachmentsComponent',
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
        attachmentsColOne: [],
        attachmentsColTwo: []
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
                if(this.eventState.attachments) {
                    const splitIndex = Math.ceil(this.eventState.attachments.length / 2);
                    this.attachmentsColOne = this.eventState.attachments.slice(0, splitIndex)
                    this.attachmentsColTwo = this.eventState.attachments.slice(splitIndex);
                }
                
                return this.preloader = false;
            }
        }
    },
}
</script>