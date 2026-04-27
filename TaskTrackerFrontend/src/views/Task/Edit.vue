<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Sidebar from '@/components/Sidebar.vue';
import { SidebarProvider, SidebarTrigger } from '@/components/ui/sidebar';
import EditTask from '@/components/EditTask.vue';
import CreateTask from '@/components/CreateTask.vue';

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();

const id: id = route.params.id;

type id = string | string[] | undefined;
type Token = string | null;
type SuccessMessage = string | null;

interface User {
  firstName: string | undefined;
  lastName: string | undefined;
}

interface TaskDetails {
  id: number | null;
  userId: number | null;
  name: string;
  description: string;
  category: string | null;
  dateSet: Date | null;
  dateDue: Date | null;
  completed: boolean | null;
}

const taskDetails = ref<TaskDetails>({
  id: null,
  userId: null,
  name: '',
  description: '',
  category: '',
  dateSet: null,
  dateDue: null,
  completed: null,
});

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
  setTaskDetails(id);
});

const setTaskDetails = async (taskId: id) => {
  try {
    const response = await axios.get(`http://127.0.0.1:8000/api/${taskId}/show`, {
      headers: {
        Authorization: `Bearer ${authStore.token}`,
      },
    });

    console.log(response);
    taskDetails.value.id = response.data.task.id;
    taskDetails.value.userId = response.data.task.user_id;
    taskDetails.value.name = response.data.task.name;
    taskDetails.value.description = response.data.task.description;
    taskDetails.value.category = response.data.task.category;
    taskDetails.value.dateSet = new Date(response.data.task.date_set);
    taskDetails.value.dateDue = new Date(response.data.task.date_due);
  } catch (error: unknown) {
    console.log(`There was an error retrieving the task information: ${error}`);
  }
};
const logout = () => {
  authStore.logout();
  goToSignIn();
};

const goToSignIn = () => {
  router.push({
    name: 'SignIn',
  });
};

const editTask = async (updatedTaskDetails: TaskDetails) => {
  try {
    const response = await axios.put(
      `http://127.0.0.1:8000/api/${updatedTaskDetails.id}/update`,
      {
        name: updatedTaskDetails.name,
        description: updatedTaskDetails.description,
        category: updatedTaskDetails.category,
        date_due: updatedTaskDetails.dateDue,
      },
      {
        headers: {
          Authorization: `Bearer ${authStore.token}`,
        },
      },
    );
    success.value = response.data.success;
  } catch (error: unknown) {
    console.log(`[TASK-UPDATE-ERROR] Something went wrong while updating the task ${error}`);
  }
};
</script>

<template>
  <SidebarProvider>
    <Sidebar :first-name="user.firstName" :last-name="user.lastName" @logout-event="logout()" />
    <main class="w-full">
      <div class="flex-justify start">
        <SidebarTrigger />
      </div>
      <EditTask :task="taskDetails" @edit-task="editTask" />
      <p v-if="success">{{ success }}</p>
    </main>
  </SidebarProvider>
</template>
