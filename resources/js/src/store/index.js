import { createApp } from 'vue'
import Vuex from 'vuex';
import apiModule from './api';

const app = createApp({});

app.use(Vuex);

const store = new Vuex.Store({
  modules: {
    api: apiModule,
  },
});

export default store;