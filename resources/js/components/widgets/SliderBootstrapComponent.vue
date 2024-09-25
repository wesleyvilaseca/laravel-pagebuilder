<template>
  <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
      <div class="carousel-inner">
        <template v-if="banners.length > 0">
          <template v-for="(banner, index) in banners" :key="index">
            <div class="carousel-item" :class="index === 0 ? 'active' : ''">
              <img
                class="d-block w-100"
                loading="lazy"
                :src="mobileScreem ? banner.image_mobile : banner.image_desktop"
                :alt="banner.name"
              />
            </div>
          </template>
        </template>
  
        <template v-if="banners.length > 1">
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
  </div>
</template>
<style scoped>
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
}

@media (max-width: 768px) {
  .carousel-item img {
     width: 100%;
     height: 100%;
     object-position: 50% 70%;
  }
}

@media (max-width: 580px) {
  .carousel-item img {
     width: 100%;
     height: 100%;
     object-position: 40% 70%;
  }
}

@media (max-width: 480px) {
  .carousel-item {
    height: 480px;
  }

  .carousel-item img {
     width: 100%;
     height: 100%;
     object-position: 50% 80%;
  }
}
</style>

<script>
export default {
    name: 'SliderBootstrapComponent',
    props: {
        gallery: {
            type: Array,
            default: []
        }
    },
    components: {},
    data: () => ({
        mobileScreem: false,
        banners: []
    }),
    setup() { },
    computed: {},
    mounted() {
        this.banners = this.gallery;
        this.updateLayout();
        window.addEventListener('resize', this.updateLayout);
    },
    created() {},
    methods: {
          updateLayout() {
            this.mobileScreem = window.innerWidth <= 480;

            this.banners = [];
            this.banners = this.gallery;
        }
    },
    watch: { },
}
</script>