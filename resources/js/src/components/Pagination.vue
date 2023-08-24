<template>
  <ul v-if="!loadingStatus && comments.length !== 0" class="pagination">
    <li :class="prevPage == null ? 'page-item disabled' : 'page-item'">
      <a class="page-link" @click="goToPage(currentPage - 1)">Previous</a>
    </li>
    <li :class="nextPage == null ? 'page-item disabled' : 'page-item'">
      <a class="page-link" @click="goToPage(currentPage + 1)">Next</a>
    </li>
  </ul>
</template>

<script>
export default {
  name: "Pagination",
  computed: {
    comments() {
      return this.$store.state.api.comments;
    },

    loadingStatus() {
      return this.$store.state.api.loadingStatus;
    },

    currentPage() {
      return this.$store.state.api.currentPage;
    },

    prevPage() {
      return this.$store.state.api.prevPage;
    },

    nextPage() {
      return this.$store.state.api.nextPage;
    },
  },
  methods: {
    goToPage(page) {
      this.$store.dispatch('fetchComments', page);
    }
  }
}
</script>

<style scoped>

</style>
