<script setup lang="ts">
import { onMounted, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Sidebar from '@/components/Sidebar.vue';
import axios from 'axios';
import { SidebarProvider, SidebarTrigger } from '@/components/ui/sidebar';
import CreateTask from '@/components/CreateTask.vue';

const authStore = useAuthStore();
const router = useRouter();

type Token = string | null;

type SuccessMessage = string | null;

interface TaskDetails {
  taskName: string;
  taskDescription: string;
  taskCategory: string | null;
  dateDue: Date | null;
}

interface User {
  firstName: string | undefined;
  lastName: string | undefined;
}

const user = reactive<User>({
  firstName: '',
  lastName: '',
});

const success = ref<SuccessMessage>(null);

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

const createTask = async (taskDetails: TaskDetails) => {
  try {
    const response = await axios.post(
      'http://127.0.0.1:8000/api/store',
      {
        name: taskDetails.taskName,
        description: taskDetails.taskDescription,
        category: taskDetails.taskCategory,
        date_due: taskDetails.dateDue,
      },
      {
        headers: {
          Authorization: `Bearer ${authStore.token}`,
        },
      },
    );

    success.value = response.data.success;

    // Comment out until the show task page is created
    // setTimeout(() => {
    //   goToViewTask()
    // }, 2000);
  } catch (error: unknown) {
    console.error(`[CREATE-ERROR] Error creating task: ${error}`);
  }
};

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
    <Sidebar :first-name="user.firstName" :last-name="user.lastName" @logout-event="logout()" />
    <main class="w-full">
      <div class="flex-justify start">
        <SidebarTrigger />
      </div>
      <CreateTask @create-task="createTask" />
      <p v-if="success">{{ success }}</p>
    </main>
  </SidebarProvider>
</template>
