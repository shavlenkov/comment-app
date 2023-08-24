<template>
  <div v-if="Object.values(errors) !== 0 && status === 'error'" class="alert alert-danger">
    <ul v-for="error in Object.values(errors)">
      <li>{{ error[0] }}</li>
    </ul>
  </div>

  <div v-if="status === 'success'" class="alert alert-success">
    {{ text }}
  </div>
</template>

<script>
export default {
  name: "Alert",
  props: ['errors', 'status', 'text']
}
</script>
