import { createRouter, createWebHistory } from 'vue-router';
import SignIn from '@/views/SignIn.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'SignIn',
      component: SignIn,
    },
  ],
});

export default router;
