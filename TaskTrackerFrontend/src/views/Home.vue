<script setup lang="ts">
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import { onMounted, reactive } from 'vue';
import { Button } from '@/components/ui/button';
import { SidebarProvider, SidebarTrigger } from '@/components/ui/sidebar';
import Sidebar from '@/components/Sidebar.vue';

const authStore = useAuthStore();
const router = useRouter();

interface User {
  firstName: string | undefined;
  lastName: string | undefined;
}

type Token = string | null;

const user = reactive<User>({
  firstName: '',
  lastName: '',
});

onMounted(async () => {
  const token: Token = localStorage.getItem('token');
  if (token) {
    authStore.setToken(token);
    await authStore.fetchUser();
    user.firstName = authStore.user?.first_name;
    user.lastName = authStore.user?.last_name;
  } else if (token === null) {
    logout();
  }
});

const logout = (): void => {
  authStore.logout();
  goToSignIn();
};

const goToSignIn = (): void => {
  router.push({
    name: 'SignIn',
  });
};
</script>

<template>
  <SidebarProvider>
    <Sidebar :first-name="user.firstName" :last-name="user.lastName" @logout-event="logout" />
    <main class="w-full">
      <div class="flex justify-start">
        <SidebarTrigger />
      </div>
      <div class="flex justify-center">
        <h1 class="text-xl">Home</h1>
      </div>
    </main>
  </SidebarProvider>
</template>
