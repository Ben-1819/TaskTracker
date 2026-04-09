<script setup lang="ts">
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import { onMounted, reactive } from 'vue';
import NavigationButton from '@/components/NavigationButton.vue';
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

const goToCreateTask = (): void => {
  // router.push({
  //   name: 'CreateTask'
  // });
};

const goToViewTasks = (): void => {
  // router.push({
  //   name: 'TaskIndex'
  // });
};

const goToCurrentTasks = (): void => {
  // router.push({
  //   name: 'CurrentTasks'
  // });
};

const goToIncompleteTasks = (): void => {
  // router.push({
  //   name: 'IncompleteTasks'
  // });
};

const goToCompletedTasks = (): void => {
  // router.push({
  //   name: 'CompletedTasks'
  // });
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
        <h1 class="text-xl pb-10">Welcome to Task Tracker</h1>
      </div>
      <div class="flex justify-center">
        <div class="grid grid-cols-2 gap-8">
          <NavigationButton @click="goToCreateTask">Create task</NavigationButton>
          <NavigationButton @click="goToViewTasks">View tasks</NavigationButton>
          <NavigationButton @click="goToCurrentTasks">Current Tasks</NavigationButton>
          <NavigationButton @click="goToIncompleteTasks">Incomplete Tasks</NavigationButton>
          <NavigationButton @click="goToCompletedTasks">Completed Tasks</NavigationButton>
        </div>
      </div>
    </main>
  </SidebarProvider>
</template>
