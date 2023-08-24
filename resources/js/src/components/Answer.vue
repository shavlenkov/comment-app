<template>
  <div>
    <ModalReply @clear-comment-data="commentData = null" :show="selectedAnswer" @close="selectedAnswer = null"
                :answerData="selectedAnswer" :commentData="null"/>
    <div v-for="answer in answers" :key="answer.id" class="answers" :style="{'marginLeft': (margin * 10) + 'px'}">
      <div class="answer">
        <div class="answer__header">
          <img class="answer__avatar" width="50" height="50" :src="getAvatarSource(answer.user.gender)" alt="">
          <div class="answer__user"><b>{{ answer.user.name }}</b></div>
          <div class="answer__date">{{ getDate(answer.created_at) }}</div>
          <div class="answer__reply"><a class="answer__reply-link" @click="selectAnswer(answer)" data-toggle="modal"
                                        data-target="#modalReply"
                                        href="#">Reply</a>
          </div>
          <div class="answer__count">
            <i class="fa-solid fa-arrow-up"></i>
            {{ !answer.answers ? 0 : answer.answers.length }}
            <i class="fa-solid fa-arrow-down"></i>
          </div>
        </div>
        <div class="answer__body">
          <div v-html="filteredHtml(answer.text)" class="answer__text"></div>
          <div v-if="answer.file" :class="getFileTypeByPath(answer.file)">
            <i style="font-size: 40px" :class="['file__icon', 'fa-solid', 'fa-' + getFileTypeByPath(answer.file)]"></i>
            <a :href="answer.file" data-lightbox="image">{{ answer.file.split('/')[answer.file.split('/').length - 1] }}</a>
          </div>
        </div>
      </div>
      <Answer v-if="answer.answers" :answers="answer.answers" :margin="margin + 1"/>
    </div>
  </div>
</template>

<script>
import ModalReply from './ModalReply.vue'

import maleImage from '../assets/male.png';
import femaleImage from '../assets/female.png';

export default {
  name: "Answer",
  props: ['answers', 'margin'],

  data() {
    return {
      selectedFile: null,
      selectedAnswer: null,
    };
  },
  methods: {
    getAvatarSource(gender) {
      return gender === 'male' ? maleImage : femaleImage;
    },

    selectFile(file) {
      this.selectedFile = file;
    },

    getFileTypeByPath(filePath) {

      const fileExtension = filePath.split('.').pop().toLowerCase();

      switch (fileExtension) {
        case 'jpg':
        case 'jpeg':
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

    selectAnswer(answer) {
      this.selectedAnswer = answer;
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
  },

  components: {
    ModalReply,
  }
}
</script>

<style scoped>
.answer {
  display: flex;
  flex-direction: column;
  border-radius: 20px;
}

.answer__user {
  margin-right: 20px;
}

.answer__header {
  display: flex;
  align-items: center;
  background: #e3e3e3;
  padding: 10px;
}

.answer__body {
  background: #fff;
  padding: 10px;
  padding-left: 0;
}

.answer__count {
  position: absolute;
  right: 0;
  margin-right: 20px;
}

.answer__reply {
  position: absolute;
  right: 0;
  margin-right: 90px;
}

.answer__reply-link {
  text-decoration: none;
}

.answer__avatar {
  margin-right: 20px;
}

.answer__reply {
  display: inline-block;
  margin-left: 10px;
}

@media (max-width: 550px) {
  .answer__header {
    flex-wrap: wrap;
  }

  .answer__reply {
    flex: 0 0 100%;
    margin-bottom: 5px;
  }

  .answer__user,
  .answer__date,
  .answer__count {
    flex: 0 0 100%;
  }
}

</style>
