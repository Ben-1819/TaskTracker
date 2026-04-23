import { createRouter, createWebHistory } from 'vue-router';
import Home from '@/views/Home.vue';
import SignIn from '@/views/SignIn.vue';
import Register from '@/views/Register.vue';
import Create from '@/views/Task/Create.vue';
import Index from '@/views/Task/Index.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home,
    },
    {
      path: '/login',
      name: 'SignIn',
      component: SignIn,
    },
    {
      path: '/register',
      name: 'Register',
      component: Register,
    },
    {
      path: '/task/create',
      name: 'CreateTask',
      component: Create,
    },
    {
      path: '/task/index',
      name: 'Tasks',
      component: Index,
    },
  ],
});

export default router;
