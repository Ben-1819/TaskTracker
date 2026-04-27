<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Button } from '@/components/ui/button';
import {
  InputGroup,
  InputGroupAddon,
  InputGroupInput,
  InputGroupTextarea,
} from '@/components/ui/input-group';
import TaskCategorySelect from './TaskCategorySelect.vue';
import DatePicker from './DatePicker.vue';

const props = defineProps<{
  task: TaskDetails;
}>();

const emit = defineEmits<{
  (e: 'editTask', payload: TaskDetails): void;
}>();

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
  id: props.task.id,
  userId: props.task.userId,
  name: props.task.name,
  description: props.task.description,
  category: props.task.category,
  dateSet: props.task.dateSet,
  dateDue: props.task.dateDue,
  completed: props.task.completed,
});

onMounted(() => {
  setTimeout(() => {
    taskDetails.value.id = props.task.id;
    taskDetails.value.userId = props.task.userId;
    taskDetails.value.name = props.task.name;
    taskDetails.value.description = props.task.description;
    taskDetails.value.category = props.task.category;
    taskDetails.value.dateSet = props.task.dateSet;
    taskDetails.value.dateDue = props.task.dateDue;
    taskDetails.value.completed = props.task.completed;
  }, 500);
});
const emitTaskEdit = () => {
  emit('editTask', { ...taskDetails.value });
};
</script>

<template>
  <div class="flex flex-col justify-center items-center align-center gap-10 pt-15 max-w-md mx-auto">
    <h1 class="text-2xl text-green-500">Edit Task</h1>
    <InputGroup>
      <InputGroupInput type="text" v-model="taskDetails.name" />
    </InputGroup>
    <InputGroup>
      <InputGroupTextarea type="text" v-model="taskDetails.description" />
    </InputGroup>

    <TaskCategorySelect
      :task-category="task.category"
      @emit-task-category="taskDetails.category = $event"
    />

    <DatePicker :initial-date="task.dateDue" @emit-due-date="taskDetails.dateDue = $event" />
    <Button variant="outline" class="text-green-600" @click="emitTaskEdit">Edit Task</Button>
  </div>
</template>
