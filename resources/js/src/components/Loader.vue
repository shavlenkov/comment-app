<template>
  <div v-if="loadingStatus" id="loading" class="spinner-grow text-primary" role="status">
    <span class="sr-only"></span>
  </div>
</template>

<script>
export default {
  name: "Loader",
  computed: {
    loadingStatus() {
      return this.$store.state.api.loadingStatus;
    },
  },
}
</script>

<style scoped>
#loading {
  position: fixed;
  top: 50%;
  left: 47%;
  width: 50px;
  height: 50px;
}
</style>
