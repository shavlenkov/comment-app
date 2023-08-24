import axios from 'axios'

const apiModule = {
  state: {
    errors: {},
    status: '',
    text: "",
    comments: [],
    loadingStatus: false,
    currentPage: 1,
    prevPage: null,
    nextPage: null,
    sortingStatus: false,
  },

  mutations: {
    SET_COMMENTS(state, comments) {
      state.comments = comments
    },

    SET_ERRORS(state, errors) {
      state.errors = errors
    },

    SET_TEXT(state, text) {
      state.text = text;
    },

    SET_STATUS(state, status) {
      state.status = status;
    },

    LOADING_STATUS(state, loadingStatus) {
      state.loadingStatus = loadingStatus;
    },

    SET_CURRENT_PAGE(state, currentPage) {
      state.currentPage = currentPage;
    },

    SET_PREV_PAGE(state, prevPage) {
      state.prevPage = prevPage;
    },

    SET_NEXT_PAGE(state, nextPage) {
      state.nextPage = nextPage;
    },

    TOGGLE_SORT(state) {
      state.sortingStatus = !state.sortingStatus;
    },
  },

  actions: {
    fetchComments({commit, state}, page = 1) {
      commit('LOADING_STATUS', true)

      return axios.get(`/api/comments?page=${page}&sort=${state.sortingStatus}`)
        .then((res) => {
          commit('SET_COMMENTS', res.data.data);
          commit('SET_CURRENT_PAGE', res.data.meta.current_page);
          commit('SET_PREV_PAGE', res.data.links.prev);
          commit('SET_NEXT_PAGE', res.data.links.next);
          commit('LOADING_STATUS', false)
        })

    },

    addComment({commit}, comment) {

      const formData = new FormData();

      formData.append('name', comment.name);
      formData.append('gender', comment.gender);
      formData.append('email', comment.email);
      formData.append('homepage', comment.homepage);
      formData.append('text', comment.text);

      localStorage.setItem('name', comment.name);
      localStorage.setItem('gender', comment.gender);
      localStorage.setItem('email', comment.email);
      localStorage.setItem('homepage', comment.homepage);

      if (comment.file) {
        formData.append('file', comment.file);
      }

      if (comment.recaptcha_token) {
        formData.append('recaptcha_token', comment.recaptcha_token);
      }

      return axios.post('/api/comments', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        }
      })
        .then((res) => {
          this.dispatch('fetchComments');
          commit('SET_ERRORS', {});
          commit('SET_TEXT', 'Comment created successfully');
          commit('SET_STATUS', 'success');
          commit('LOADING_STATUS', false);
        }).catch((err) => {
          commit('SET_ERRORS', err.response.data.errors);
          commit('SET_STATUS', 'error');
        })

    },

    addAnswer({commit}, answer) {

      const formData = new FormData();

      formData.append('name', answer.name);
      formData.append('gender', answer.gender);
      formData.append('email', answer.email);
      formData.append('homepage', answer.homepage);

      formData.append('text', answer.text);

      localStorage.setItem('name', answer.name);
      localStorage.setItem('gender', answer.gender);
      localStorage.setItem('email', answer.email);
      localStorage.setItem('homepage', answer.homepage);

      if (answer.file) {
        formData.append('file', answer.file);
      }

      if (answer.recaptcha_token) {
        formData.append('recaptcha_token', answer.recaptcha_token);
      }

      if (!answer.commentId) {
        return axios.post(`/api/comments/${answer.id}/answers`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          }
        })
          .then((res) => {
            this.dispatch('fetchComments');
            commit('SET_ERRORS', {});
            commit('SET_TEXT', 'Answer created successfully');
            commit('SET_STATUS', 'success');
            commit('LOADING_STATUS', false);
          }).catch((err) => {
            commit('SET_ERRORS', err.response.data.errors);
            commit('SET_STATUS', 'error');
          })

      } else {
        return axios.post(`/api/comments/${answer.commentId}/answers/${answer.id}`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          }
        })
          .then((res) => {
            this.dispatch('fetchComments');
            commit('SET_ERRORS', {});
            commit('SET_STATUS', 'success');
            commit('SET_TEXT', 'Answer created successfully');
            commit('LOADING_STATUS', false);
          }).catch((err) => {
            commit('SET_ERRORS', err.response.data.errors);
            commit('SET_STATUS', 'error');
          })
      }
    }
  },
};

export default apiModule;
