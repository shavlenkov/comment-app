<template>
  <div>
    <ModalReply :show="selectedComment" @close="selectedComment = null" :commentData="selectedComment"
                :answerData="null"/>
    <div class="comments" v-for="comment in comments" :key="comment">
      <div class="comment">
        <div class="comment__header">
          <img class="comment__avatar" width="50" height="50" :src="getAvatarSource(comment.user.gender)" alt="">
          <div class="comment__user"><b>{{ comment.user.name }}</b></div>
          <div class="comment__date">{{ getDate(comment.created_at) }}</div>
          <div class="comment__reply"><a @click="selectComment(comment)" data-toggle="modal" data-target="#modalReply"
                                         href="#">Reply</a></div>
          <div class="comment__count"><i class="fa-solid fa-arrow-up"></i> {{ comment.answers.length }} <i
            class="fa-solid fa-arrow-down"></i></div>
        </div>
        <div class="comment__body">
          <div v-html="filteredHtml(comment.text)" class="comment__text"></div>
          <div v-if="comment.file" class="file">
            <i style="font-size: 40px" :class="['file__icon', 'fa-solid', 'fa-' + getFileTypeByPath(comment.file)]"></i>
            <a :href="comment.file" data-lightbox="image">{{ comment.file.split('/')[comment.file.split('/').length - 1] }}</a>
          </div>
        </div>
      </div>
      <Answer v-if="comment.answers" :answers="comment.answers" :margin="2"/>
    </div>
  </div>
</template>

<script>
import Answer from "./Answer.vue";
import Alert from './Alert.vue'
import ModalReply from './ModalReply.vue'
import Pagination from './Pagination.vue'

import maleImage from '../assets/male.png';
import femaleImage from '../assets/female.png';

export default {
  name: "Comments",

  data() {
    return {
      selectedFile: null,
      selectedComment: null,
    };
  },

  mounted() {
    this.$store.dispatch('fetchComments', this.$store.state.api.currentPage); // Fetch the comments for the current page
  },

  computed: {
    comments() {
      return this.$store.state.api.comments;
    },
  },

  methods: {

    getAvatarSource(gender) {
      return gender === 'male' ? maleImage : femaleImage;
    },

    filteredHtml(html) {
      const allowedTags = ["strong", "code", "i"];
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, "text/html");

      const elements = doc.body.querySelectorAll("*");
      elements.forEach((element) => {
        if (!allowedTags.includes(element.tagName.toLowerCase()) &&
          !(element.tagName.toLowerCase() === "a" && element.hasAttribute("href"))) {
          const textNode = document.createTextNode(element.outerHTML);
          element.replaceWith(textNode);
        }
      });

      return doc.body.innerHTML;
    },

    sortComments() {
      this.$store.commit('TOGGLE_SORT');
      this.$store.dispatch('fetchComments', this.$store.state.api.currentPage);
    },

    selectFile(file) {
      this.selectedFile = file;
    },

    getFileTypeByPath(filePath) {

      const fileExtension = filePath.split('.').pop().toLowerCase();

      switch (fileExtension) {
        case 'jpeg':
        case 'jpg':
        case 'png':
        case 'gif':
          return 'image';
        case 'txt':
          return 'file';
        case 'pdf':
          return 'pdf';
        case 'mp4':
        case 'avi':
        case 'mkv':
          return 'video';
        default:
          return 'unknown';
      }
    },

    selectComment(comment) {
      this.selectedComment = comment;
    },

    getDate(timestamp) {
      const date = new Date(timestamp);

      const day = date.getUTCDate();
      const month = date.getUTCMonth() + 1;
      const year = date.getUTCFullYear() % 100;
      const hours = date.getUTCHours();
      const minutes = date.getUTCMinutes();

      const formattedDate = `${day < 10 ? '0' : ''}${day}.${month < 10 ? '0' : ''}${month}.${year < 10 ? '0' : ''}${year}`;
      const formattedTime = `${hours < 10 ? '0' : ''}${hours}:${minutes < 10 ? '0' : ''}${minutes}`;

      return `${formattedDate} в ${formattedTime}`;
    },
  },

  components: {
    Answer,
    Alert,
    ModalReply,
    Pagination
  }
}
</script>

<style>

.comment {
  display: flex;
  flex-direction: column;
  border-radius: 20px;
}

.comment__user {
  margin-right: 20px;
}

.comment__avatar {
  margin-right: 20px;
}

.comment__header {
  display: flex;
  align-items: center;
  background: #e3e3e3;
  padding: 10px;
  position: relative;
}

.comment__reply {
  position: absolute;
  right: 0;
  margin-right: 90px;
}

.comment__count {
  position: absolute;
  right: 0;
  margin-right: 20px;
}

.comment__body {
  background: #fff;
  padding: 10px;
  padding-left: 0;
}

.file {
  display: flex;
  align-items: center
}

.file__icon {
  margin-right: 10px;
}

.comment__reply {
  display: inline-block;
  margin-left: 10px;
}

.comment__text {
  word-wrap: break-word;
}

@media (max-width: 550px) {
  .comment__header {
    flex-wrap: wrap;
  }

  .comment__reply {
    flex: 0 0 100%;
    margin-bottom: 5px;
  }

  .comment__user,
  .comment__date,
  .comment__count {
    flex: 0 0 100%;
  }
}

</style>
