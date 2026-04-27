<script setup lang="ts">
import { reactive, onMounted, ref } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import Sidebar from '@/components/Sidebar.vue';
import { SidebarProvider, SidebarTrigger } from '@/components/ui/sidebar';
import axios from 'axios';
import TaskCard from '@/components/TaskCard.vue';

type Token = string | null;
const authStore = useAuthStore();
const router = useRouter();

interface User {
  firstName: string | undefined;
  lastName: string | undefined;
}

interface TaskAPI {
  id: number;
  userId: number;
  name: string;
  description: string;
  category: string;
  date_set: Date;
  date_due: Date;
  completed: boolean;
}

interface Task {
  id: number;
  userId: number;
  name: string;
  description: string;
  category: string;
  dateSet: Date;
  dateDue: Date;
  completed: boolean;
}

const user = reactive<User>({
  firstName: '',
  lastName: '',
});

const tasks = ref<Task[]>([]);

onMounted(async () => {
  const token: Token = localStorage.getItem('token');
  if (token) {
    authStore.setToken(token);
    await authStore.fetchUser();
    user.firstName = authStore.user?.first_name;
    user.lastName = authStore.user?.last_name;
    getIncompleteTasks();
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

const getIncompleteTasks = async (): Promise<void> => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/incomplete', {
      headers: {
        Authorization: `Bearer ${authStore.token}`,
      },
    });
    console.log('Incomplete tasks retrieved');

    tasks.value = response.data.incomplete_tasks.map((task: TaskAPI) => ({
      id: task.id,
      userId: task.userId,
      name: task.name,
      description: task.description,
      category: task.category,
      dateSet: new Date(task.date_set),
      dateDue: new Date(task.date_due),
      completed: task.completed,
    }));
  } catch (error: unknown) {
    console.error(
      `[FETCH-INCOMPLETE-ERROR] An error occurred while fetching incomplete tasks: ${error}`,
    );
  }
};

const goToEditTask = (taskId: number): void => {
  router.push({
    name: 'EditTask',
    params: {
      id: taskId,
    },
  });
};
</script>

<template>
  <SidebarProvider>
    <Sidebar :first-name="user.firstName" :last-name="user.lastName" @logout-event="logout()" />
    <main class="w-full">
      <div class="flex justify-start">
        <SidebarTrigger />
      </div>
      <h1 class="text-2xl text-center">Incomplete Tasks</h1>
      <div v-if="tasks.length === 0" class="mt-20">
        <h2 class="text-xl text-center text-green-600">You have no incomplete tasks.</h2>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <TaskCard
          v-for="task in tasks"
          :key="task.id"
          :task="task"
          @edit-task="goToEditTask(task.id)"
        />
      </div>
    </main>
  </SidebarProvider>
</template>
