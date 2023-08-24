import { createRouter, createWebHistory } from 'vue-router'
import MainPage from '@/pages/MainPage.vue'
import PreviewPage from "@/pages/PreviewPage.vue"

const routes = [
  {
    path: '/',
    name: 'main',
    component: MainPage
  },
  {
    path: '/preview',
    name: 'preview',
    component: PreviewPage
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
