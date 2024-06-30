<template>
    <div>teste vue <span class="teste" @click.prevent="testeFunction">componet js</span> </div>
</template>
<style scoped>
.test {
    border: 1px solid red;
}
</style>

<script>
export default {
    props: [],
    components: {  },
    data: () => ({}),
    computed: {},
    mounted() { },
    created() { },
    methods: {
        testeFunction() {
            alert('alert from vue component');
        }
    },
}
</script>