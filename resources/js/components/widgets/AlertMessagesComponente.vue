<template>
    <div>
        <!-- Mensagens de erro -->
        <div v-if="errors.length" class="alert alert-danger alert-dismissible fade show" role="alert">
            <p v-for="(error, index) in errors" :key="index">{{ error }}</p>
            <button type="button" class="close" @click="closeAlert('errors')" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <!-- Mensagem de sucesso -->
        <div v-if="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
            <p>{{ successMessage }}</p>
            <button type="button" class="close" @click="closeAlert('success')" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <!-- Mensagem de erro específica -->
        <div v-if="errorMessage" class="alert alert-danger alert-dismissible fade show" role="alert">
            <p>{{ errorMessage }}</p>
            <button type="button" class="close" @click="closeAlert('error')" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <!-- Mensagem de aviso -->
        <div v-if="warningMessage" class="alert alert-warning alert-dismissible fade show" role="alert">
            <p>{{ warningMessage }}</p>
            <button type="button" class="close" @click="closeAlert('warning')" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        errors: {
            type: Array,
            default: () => [],
        },
        successMessage: {
            type: String,
            default: '',
        },
        errorMessage: {
            type: String,
            default: '',
        },
        warningMessage: {
            type: String,
            default: '',
        },
    },
    methods: {
        closeAlert(type) {
            if (type === 'errors') {
                this.$emit('update:errors', []);
            } else if (type === 'success') {
                this.$emit('update:successMessage', '');
            } else if (type === 'error') {
                this.$emit('update:errorMessage', '');
            } else if (type === 'warning') {
                this.$emit('update:warningMessage', '');
            }
        },
    },
};
</script>

<style scoped>
.alert {
    margin-bottom: 1rem;
}
</style>