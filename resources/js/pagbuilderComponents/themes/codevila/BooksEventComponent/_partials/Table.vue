<template>
  <div class="data-table">
    <div class="main-table">
      <table class="table">
        <thead>
          <tr>
            <th class="table-head">#</th>
            <th v-for="column in columns" :key="column" @click="sortByColumn(column.column)"
                class="table-head">
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
            <td class="lead text-center" :colspan="columns.length + 2">No data found.</td>
          </tr>
          <tr v-for="(data, key1) in tableData" :key="data.id" class="m-datatable__row">
            <td>{{ serialNumber(key1) }}</td>
            <template v-for="(value, key) in data" :key="key">
              <template v-if="key == 'authors'">
                <td v-if="data.authors && data.authors.length > 0">
                  <ul>
                    <li v-for="author in data.authors" :key="author.first_name + author.last_name">
                      {{ author.first_name }} {{ author.last_name }}
                    </li>
                  </ul>
                </td>
              </template>
              <template v-else>  <td>{{ value }}</td> </template>
            </template>
          </tr>
        </tbody>
      </table>
    </div>
    <nav v-if="pagination && tableData.length > 0">
      <ul class="pagination">
        <li class="page-item" :class="{'disabled': currentPage === 1}">
          <a class="page-link" href="#" @click.prevent="changePage(currentPage - 1)">Anterior</a>
        </li>
        <li v-for="page in pagesNumber" :key="page" class="page-item"
            :class="{'active': page === pagination.meta.current_page}">
          <a href="javascript:void(0)" @click.prevent="changePage(page)" class="page-link">{{ page }}</a>
        </li>
        <li class="page-item" :class="{'disabled': currentPage === pagination.meta.last_page}">
          <a class="page-link" href="#" @click.prevent="changePage(currentPage + 1)">Próximo</a>
        </li>
        <span style="margin-top: 8px;"> &nbsp; <i>Mostrado {{ totalData }} of {{ pagination.meta.total }} registros.</i></span>
      </ul>
    </nav>
  </div>
</template>

<script>
export default {
  props: {
    tableData: { type: Array, default: () => [] },
    pagination: { type: Object, default: () => ({ meta: { to: 1, from: 1 } }) },
    columns: { type: Array, required: true },
    sortedColumn: { type: String, default: '' },
    order: { type: String, default: 'asc' },
    currentPage: { type: Number, default: 1 },
    perPage: { type: Number, default: 5 }
  },
  computed: {
    pagesNumber() {
      if (!this.pagination.meta.to) {
        return []
      }
      let from = this.pagination.meta.current_page - this.offset
      if (from < 1) {
        from = 1
      }
      let to = from + (this.offset * 2)
      if (to >= this.pagination.meta.last_page) {
        to = this.pagination.meta.last_page
      }
      let pagesArray = []
      for (let page = from; page <= to; page++) {
        pagesArray.push(page)
      }
      return pagesArray
    },
    totalData() {
      return (this.pagination.meta.to - this.pagination.meta.from) + 1
    }
  },
  methods: {
    serialNumber(key) {
      return (this.currentPage - 1) * this.perPage + 1 + key
    },
    changePage(pageNumber) {
      this.$emit('update:currentPage', pageNumber)
    },
    sortByColumn(column) {
      const newOrder = (this.sortedColumn === column && this.order === 'asc') ? 'desc' : 'asc'
      this.$emit('update:sortedColumn', column)
      this.$emit('update:order', newOrder)
    }
  },
  filters: {
    columnHead(value) {
      return value.split('_').join(' ').toUpperCase()
    }
  },
  name: 'DataTable'
}
</script>

<style scoped>
</style>
