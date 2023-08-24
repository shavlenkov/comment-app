<template>
  <div class="container-fluid">
    <button data-toggle="modal" data-target="#modalCreate" @click="this.statusModal = true" type="button" class="btn btn-success">
      <i class="fa-solid fa-plus"></i> Create comment
    </button>
    <br/><br/>
    <SortButton/>
    <br/><br/>
    <Alert :errors="errors" :status="status" :text="text"/>
    <Comments/>
    <Pagination/>
    <Loader/>
    <ModalCreate :show="statusModal" @close="statusModal = false"/>
  </div>
</template>

<script>
import Alert from "@/components/Alert.vue";
import ModalCreate from "@/components/ModalCreate.vue";
import SortButton from "@/components/SortButton.vue";
import Loader from "@/components/Loader.vue";
import Comments from "@/components/Comments.vue";
import Pagination from "@/components/Pagination.vue";

export default {
  name: "MainPage",

  data() {
    return {
      statusModal: false
    }
  },
  computed: {
    errors() {
      return this.$store.state.api.errors;
    },

    status() {
      return this.$store.state.api.status;
    },

    text() {
      return this.$store.state.api.text;
    },
  },

  components: {
    Pagination,
    Comments,
    Loader,
    SortButton,
    ModalCreate,
    Alert
  }
}
</script>

<style scoped>

</style>
