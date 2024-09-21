<template>
      <div class="container pt-5 pb-5">
          <template v-if="!preloader">
            <Table
            :tableData="books"
            :pagination="pagination"
            :columns="columns"
            :sortedColumn="sortedColumn"
            :order="order"
            :currentPage="currentPage"
            :perPage="perPage"
            :searchFilters="searchFilters"
            @update:currentPage="handlePageChange"
            @update:sortedColumn="handleSortChange"
            @update:order="handleOrderChange"
            @search="handleSearch"
            @update:perPage="handlePerPageChange"
             />
          </template>

          <template v-if="preloader">
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
import Table from './_partials/Table.vue';
import { Http } from '../../../../config/axiosConfig.js';

export default {
    name: 'BooksEventComponent',
    props: {
        event: {
            type: String,
            default: ''
        }
    },
    components: {
        VueSkeletonLoader,
        Table
    },
    data: () => ({
        searchFilters: [
            { label: 'Livro', value: 'name' },
            { label: 'Assunto', value: 'subject' },
            { label: 'Editora', value: 'publisher_name' },
            { label: 'ISBN', value: 'isbn' },
            { label: 'Preço', value: 'price' },
            { label: 'Autor', value: 'author_name' }
        ],
        columns: [
            {
                title: 'Nome',
                column: 'name'
            },
            {
                title: 'Assunto',
                column: 'subject'
            },
            {
                title: 'ISBN',
                column: 'isbn',

            },
            {
                title: 'Preço',
                column: 'price'
            },
            {
                title: 'Preço do desconto',
                column: 'price_discount'
            },
            {
                title: 'Link',
                column: 'link'
            },
            {
                title:  'Autores(as)',
                column: 'author'
            }
        ],
        preloader: true,
        books: [],
        pagination: {},
        sortedColumn: 'name',
        order: 'asc',
        currentPage: 1,
        perPage: 10,
        filterSelected: "",
        search: "",
    }),
    setup() {},
    computed: {},
    mounted() {
        if (!this.event) {
            this.preloader = false;
            return;
        }
        this.getBooks();
    },
    created() {},
    methods: {
        async getBooks() {
           try {
            const params = { 
                flag: this.event,
                page: this.currentPage,
                column: this.sortedColumn,
                order: this.order,
                per_page: this.perPage,
                };

            params[this.filterSelected] = this.search;
    
            const { data } = await Http.get('event-books', { params });
            this.books = data.data
            this.pagination = data
           } catch (error) {
            this.preloader = false;
            console.log(error)
           }
        },
        handlePerPageChange(perPage) {
            this.pagination = { meta: { to: 1, from: 1 }};
            this.sortedColumn = 'name'; 
            this.order = 'asc';
            this.currentPage = 1;
            this.perPage = perPage;
            this.getBooks();
        },
         handlePageChange(pageNumber) {
            this.currentPage = pageNumber
            this.getBooks()
        },
        handleSortChange(column) {
            this.sortedColumn = column
            this.getBooks()
        },
        handleOrderChange(order) {
            this.order = order
            this.getBooks()
        },
        handleSearch(search, selectedFilter) {
            this.filterSelected = selectedFilter,
            this.search = search;
            this.getBooks();
        }
    },
    watch: {
        books() {
            if(this.books.length > 0) {
                this.preloader = false;
            }
        }
    },
}
</script>