<template>
  <div class="mb-3">
    <vue-recaptcha @verify="onRecaptchaVerify" ref="recaptcha" :sitekey="this.sitekey"/>
  </div>
</template>

<script>
import { VueRecaptcha } from 'vue-recaptcha';

export default {
  name: "Recaptcha",
  props: ['onRecaptchaVerify'],
  data() {
    return {
      sitekey: import.meta.env.VITE_APP_RECAPTCHA_SITEKEY
    }
  },
  methods: {
    reset() {
      this.$refs.recaptcha.reset();
    }
  },
  components: {
    VueRecaptcha
  }
}
</script>
