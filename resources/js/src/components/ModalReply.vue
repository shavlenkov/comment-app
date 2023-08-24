<template>
  <Modal>
    <div id="modalReply" v-if="show" class="modal fade" data-backdrop="static" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Create answer</h5>
            <button @click="closeModal" type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="d-flex">
              <div class="col-lg-2 mb-3">
                <label for="gender" class="form-label">Male</label>
                <input v-model="gender" class="form-check-input" type="radio" value="male" name="gender">
              </div>
              <div class="col-lg-6 mb-3">
                <label for="gender" class="form-label">Female</label>
                <input v-model="gender" class="form-check-input" type="radio" value="female" name="gender">
              </div>
            </div>
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input ref="name" v-model="name" name="name" type="text" @input="checkName" class="form-control" id="name"
                     placeholder="Pavel">
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input ref="email" v-model="email" name="email" type="email" @input="checkEmail" class="form-control"
                     id="email" placeholder="pavel@gmail.com">
            </div>
            <div class="mb-3">
              <label for="homepage" class="form-label">Homepage</label>
              <input ref="homepage" v-model="homepage" name="homepage" type="url" @input="checkHomepage" class="form-control"
                     id="homepage" placeholder="http://localhost:8000">
            </div>
            <div class="mb-3">
              <div class="btn-group btn-group-toggle d-flex btn-group-sm w-25" data-toggle="buttons">
                <button class="btn btn-secondary" @click="this.text +=`<a href=''></a>`">a</button>
                <button class="btn btn-secondary" @click="this.text +='<strong></strong>'">strong</button>
                <button class="btn btn-secondary" @click="this.text +='<i></i>'">i</button>
                <button class="btn btn-secondary" @click="this.text +='<code></code>'">code</button>
              </div>
              <label for="text" class="form-label">Text</label>
              <textarea ref="text" style="resize: none; height: 150px" v-model="text" @input="checkText" name="text"
                        type="text" class="form-control" id="text" placeholder="Hello!"></textarea>
            </div>
            <div class="mb-3">
              <label for="file" class="form-label">File</label>

              <input accept="image/jpeg, image/png, image/png, .txt" @change="handleFileSelect" name="file" type="file"
                     class="form-control" id="file">
            </div>
            <Recaptcha ref="recaptcha" :onRecaptchaVerify="onRecaptchaVerify"/>

          </div>
          <div class="modal-footer" ref="modalFooter">
            <button :disabled="isPreviewDisabled" @click="redirectToPreviewPage" class="btn btn-danger">Preview</button>
            <button :disabled="!success.name || !success.homepage || !success.email || !success.text" type="button" data-dismiss="modal"
                    class="btn btn-primary" @click="createAnswer">Submit</button>
          </div>
        </div>
      </div>
    </div>
  </Modal>
</template>

<script>
import Modal from './Modal.vue'
import Recaptcha from './Recaptcha.vue'

export default {
  name: "ModalCreate",
  props: ['show', 'commentData', 'answerData'],

  updated() {
    if(this.name !== '' || this.email !== '' || this.homepage !== '') {
      this.checkName();
      this.checkEmail();
      this.checkHomepage();
    }

  },

  data() {
    return {
      success: {
        name: false,
        email: false,
        text: false,
        homepage: false
      },
      name: localStorage.getItem('name') || '',
      email: localStorage.getItem('email') || '',
      homepage: localStorage.getItem('homepage') || '',
      text: '',
      gender: localStorage.getItem('gender'),
      selectedFile: null,
      recaptchaToken: null
    }
  },

  computed: {
    isPreviewDisabled() {
      return !(this.name && this.text && this.gender) || !(this.success.text);
    },
  },

  methods: {

    onRecaptchaVerify(response) {
      this.recaptchaToken = response;
    },

    redirectToPreviewPage() {
      const data = {
        name: this.name,
        gender: this.gender,
        email: this.email,
        text: this.text,
        file: this.selectedFile ? this.selectedFile.name : ""
      };

      const previewURL = this.$router.resolve({
        name: 'preview',
        query: data,
      }).href;

      window.open(previewURL, '_blank');

    },

    handleFileSelect(event) {
      this.selectedFile = event.target.files[0];
    },

    closeModal() {
      this.$emit('close');
    },

    checkHTMLValidity(text) {
      const regex = /<([a-z]+)(?:\s+[^>]+)?>|<\/([a-z]+)>/gi;
      const stack = [];

      let match;
      while ((match = regex.exec(text)) !== null) {
        const [openingTag, closingTag] = match.slice(1);
        if (openingTag) {
          stack.push(openingTag.toLowerCase());
        } else if (closingTag) {
          if (stack.length === 0 || stack.pop() !== closingTag.toLowerCase()) {
            return false;
          }
        }
      }

      return stack.length === 0;
    },

    checkName() {
      if (/^[a-zA-Z0-9]+$/.test(this.name)) {
        this.success.name = true;
        const inputElement = this.$refs.name;
        inputElement.classList.remove('is-invalid')
        inputElement.classList.add('is-valid')
      } else {
        this.success.name = false;
        const inputElement = this.$refs.name;
        inputElement.classList.remove('is-valid')
        inputElement.classList.add('is-invalid')
      }
    },

    checkEmail() {
      if (/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(this.email)) {
        this.success.email = true;
        const inputElement = this.$refs.email;
        inputElement.classList.remove('is-invalid')
        inputElement.classList.add('is-valid')
      } else {
        this.success.email = false;
        const inputElement = this.$refs.email;
        inputElement.classList.remove('is-valid')
        inputElement.classList.add('is-invalid')
      }
    },

    checkHomepage() {
      if (/^(http|https):\/\/([\w.-]+\.[a-zA-Z]{2,})(\/[\w\/.-]*)?(\?[\w=&-]*)?(#[\w-]*)?$/.test(this.homepage) || this.homepage === '') {
        this.success.homepage = true;
        const inputElement = this.$refs.homepage;
        inputElement.classList.remove('is-invalid')
        inputElement.classList.add('is-valid')
      } else {
        this.success.homepage = false;
        const inputElement = this.$refs.homepage;
        inputElement.classList.remove('is-valid')
        inputElement.classList.add('is-invalid')
      }
    },

    checkText() {

      let textCopy = this.text;

      if (!this.checkHTMLValidity(this.text) || textCopy === '') {
        this.success.text = false;
        const inputElement = this.$refs.text;
        inputElement.classList.add('is-invalid')
        inputElement.classList.remove('is-valid')
      } else {
        this.success.text = true;
        const inputElement = this.$refs.text;
        inputElement.classList.remove('is-invalid');
        inputElement.classList.add('is-valid')
        this.text = textCopy;
      }

    },

    createAnswer() {

      console.log(this)

      this.$refs.recaptcha.reset()

      if (this.commentData && !this.answerData) {
        this.$store.dispatch('addAnswer', {
          id: this.commentData.id,
          name: this.name,
          gender: this.gender,
          email: this.email,
          homepage: this.homepage,
          text: this.text,
          file: this.selectedFile,
          recaptcha_token: this.recaptchaToken,
        });
      } else if (this.answerData && !this.commentData) {

        this.$store.dispatch('addAnswer', {
          id: this.answerData.id,
          name: this.name,
          commentId: this.answerData.comment_id,
          gender: this.gender,
          email: this.email,
          homepage: this.homepage,
          text: this.text,
          file: this.selectedFile,
          recaptcha_token: this.recaptchaToken,
        });
      }

      this.text = '';
      this.selectedFile = null;
      this.recaptchaToken = null;

      this.$refs.name.classList.remove('is-valid');
      this.$refs.email.classList.remove('is-valid');
      this.$refs.homepage.classList.remove('is-valid');
      this.$refs.text.classList.remove('is-valid');

      this.closeModal();

    }
  },

  components: {
    Modal,
    Recaptcha
  }
}
</script>

<style scoped>

</style>
