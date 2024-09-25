<template>
  <div class="data-table">
    <div class="filter-section">
      <div class="row">
        <div class="col-md-2">
          <select v-model="perPageModel" @change="changePerPage" class="form-select mb-2">
            <option v-for="num in perPageOptions" :key="num" :value="num">{{ num }}</option>
          </select>
        </div>
        <div class="col-md-6">
          <input v-model="searchQuery" class="form-control mb-2" type="text" placeholder="Buscar..." @keydown.enter.prevent="search()" />
        </div>
        <div class="col-md-2">
          <select v-model="selectedFilter" class="form-select mb-2">
            <option v-for="filter in searchFilters" :key="filter.value" :value="filter.value">
              {{ filter.label }}
            </option>
          </select>
        </div>
        <div class="col-md-2 col-sm-12">
            <button @click="search" class="btn btn-dark btn-block">Buscar</button>
        </div>
      </div>
    </div>

    <div class="main-table mt-3 mb-3">
      <table class="table">
        <thead>
          <tr>
            <th v-for="column in columns" :key="column" @click="sortByColumn(column.column)" class="table-head">
              {{ column.title }}
              <span v-if="column.column === sortedColumn">
                <i v-if="order === 'asc'" class="fas fa-arrow-up"></i>
                <i v-else class="fas fa-arrow-down"></i>
              </span>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="tableData.length === 0">
            <td class="lead text-center" :colspan="columns.length + 2">Não há resultado</td>
          </tr>
          <tr v-for="(data, key1) in tableData" :key="data.id" class="m-datatable__row">
            <td v-for="column in columns" :key="column.column">
              <template v-if="column.column == 'link'">
                <a :href="data[column.column]" target="_blank"> Veja no site</a>
              </template>
              <template v-else>
                  {{ data[column.column] }}
              </template>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <nav v-if="pagination && tableData.length > 0">
      <ul class="pagination">
        <li class="page-item" :class="{'disabled': currentPage === 1}">
          <a class="page-link" href="#" @click.prevent="changePage(currentPage - 1)">Anterior</a>
        </li>
        <li v-for="page in pagesNumber" :key="page" class="page-item" :class="{'active': page === pagination.meta.current_page}">
          <a href="javascript:void(0)" @click.prevent="changePage(page)" class="page-link">{{ page }}</a>
        </li>
        <li class="page-item" :class="{'disabled': currentPage === pagination.meta.last_page}">
          <a class="page-link" href="#" @click.prevent="changePage(currentPage + 1)">Próximo</a>
        </li>
        <span style="margin-top: 8px;">&nbsp; <i>{{ pagination.meta.to }} de {{ pagination.meta.total }} registros.</i></span>
      </ul>
    </nav>
  </div>
</template>

<style scoped>
  .main-table table {
    font-size: 0.80rem;
    white-space: nowrap;
    display: block;
    overflow-x: auto;
  }

  .btn-dark {
    background-color: #000;
  }

  .btn-dark:hover {
      background-color: #343a40;
  }

  @media (max-width: 768px) {
    .main-table table {
      font-size: 0.75rem;
    }
  }
</style>

<script>
export default {
  props: {
    searchFilters: { type: Array, default: () => [] },
    tableData: { type: Array, default: () => [] },
    pagination: { type: Object, default: () => ({ meta: { to: 1, from: 1 } }) },
    columns: { type: Array, required: true },
    sortedColumn: { type: String, default: '' },
    order: { type: String, default: 'asc' },
    currentPage: { type: Number, default: 1 },
    perPage: { type: Number, default: 10 }
  },
  data() {
    return {
      selectedFilter: '',
      perPageModel: '',
      searchQuery: '',
      perPageOptions: [10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
    };
  },
  mounted() {
    this.selectedFilter = this.searchFilters[0].value;
    this.perPageModel = this.perPage
  },
  computed: {
    pagesNumber() {
      if (!this.pagination.meta.to) {
        return [];
      }
      let from = this.pagination.meta.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }
      let to = from + this.offset * 2;
      if (to >= this.pagination.meta.last_page) {
        to = this.pagination.meta.last_page;
      }
      let pagesArray = [];
      for (let page = from; page <= to; page++) {
        pagesArray.push(page);
      }
      return pagesArray;
    },
    totalData() {
      return this.pagination.meta.to - this.pagination.meta.from + 1;
    }
  },
  methods: {
    serialNumber(key) {
      return (this.currentPage - 1) * this.perPage + 1 + key;
    },
    changePage(pageNumber) {
      this.$emit('update:currentPage', pageNumber);
    },
    sortByColumn(column) {
      const newOrder = this.sortedColumn === column && this.order === 'asc' ? 'desc' : 'asc';
      this.$emit('update:sortedColumn', column);
      this.$emit('update:order', newOrder);
    },
    search() {
      this.$emit('search', this.searchQuery, this.selectedFilter);
    },
    changePerPage() {
      this.$emit('update:perPage', this.perPageModel);
    }
  }
};
</script>

<style scoped>
</style>
