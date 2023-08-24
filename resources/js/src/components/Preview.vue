<template>
  <div class="cblock">
    <div class="block__header">
      <img class="block__avatar" width="50" height="50" :src="getAvatarSource($route.query.gender)" alt="">
      <div class="block__user"><b>{{ $route.query.name }}</b></div>
      <div class="block__date">{{ getDate(new Date()) }}</div>
      <div class="block__reply"><a href="#">Reply</a></div>
      <div class="block__count"><i class="fa-solid fa-arrow-up"></i> 0 <i class="fa-solid fa-arrow-down"></i></div>
    </div>
    <div class="block__body">
      <div v-html="filteredHtml($route.query.text)" class="block_text"></div>
      <div v-if="$route.query.file" class="file">
        <i style="font-size: 40px" :class="['file__icon', 'fa-solid', 'fa-' + getFileTypeByPath($route.query.file)]"></i>
        <a href="#" data-lightbox="image">{{ generateRandomString(40) + "." + $route.query.file.split('.')[1] }}</a>
      </div>
    </div>
  </div>
</template>

<script>
import maleImage from "../assets/male.png";
import femaleImage from "../assets/female.png";

export default {
  name: "Preview",
  methods: {

    getAvatarSource(gender) {
      return gender === 'male' ? maleImage : femaleImage;
    },

    generateRandomString(length) {
      const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
      let randomString = '';

      for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * characters.length);
        randomString += characters.charAt(randomIndex);
      }

      return randomString;
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

    hasSpecialTags(text) {
      const tagsToCheck = ['<i>', '<strong>', '<code>'];

      for (const tag of tagsToCheck) {
        if (text.includes(tag)) {
          return true;
        }
      }

      return false;
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
    }
  }
}

</script>

<style scoped>

.block{
  display: flex;
  flex-direction: column;
  border-radius: 20px;
}

.block__user {
  margin-right: 20px;
}

.block__avatar {
  margin-right: 20px;
}

.block__header {
  display: flex;
  align-items: center;
  background: #e3e3e3;
  padding: 10px;
  position: relative;
}

.block__reply {
  position: absolute;
  right: 0;
  margin-right: 90px;
}

.block__count {
  position: absolute;
  right: 0;
  margin-right: 20px;
}

.block__body {
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

.block__reply {
  display: inline-block;
  margin-left: 10px;
}

@media (max-width: 550px) {
  .block__header {
    flex-wrap: wrap;
  }

  .block__reply {
    flex: 0 0 100%;
    margin-bottom: 5px;
  }

  .block__user,
  .block__date,
  .block__count {
    flex: 0 0 100%;
  }
}

</style>
