<template>
  <button v-if="comments.length !== 0"  class="btn btn-primary" @click="sortComments"><i :class="sortingStatus ? 'fa-solid fa-sort-up' : 'fa-solid fa-sort-down'"></i> Sort</button>
</template>

<script>
export default {
  name: "SortButton",
  computed: {
    comments() {
      return this.$store.state.api.comments;
    },

    sortingStatus() {
      return this.$store.state.api.sortingStatus;
    }
  },
  methods: {
    sortComments() {
      this.$store.commit('TOGGLE_SORT');
      this.$store.dispatch('fetchComments', this.$store.state.api.currentPage);
      this.$store.commit('LOADING_STATUS', false);
    },
  }
}
</script>

<style scoped>

</style>
