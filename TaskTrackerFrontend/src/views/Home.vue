<script setup lang="ts">
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import { onMounted, reactive } from 'vue';
import { Button } from '@/components/ui/button';

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
  <div class="flex justify-center">{{ user.firstName }} {{ user.lastName }}</div>
  <Button @click="logout">Logout</Button>
</template>
