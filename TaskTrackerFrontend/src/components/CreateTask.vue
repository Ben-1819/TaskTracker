<script setup lang="ts">
import { ref, reactive } from 'vue';
import { Button } from './ui/button';
import {
  InputGroup,
  InputGroupAddon,
  InputGroupInput,
  InputGroupTextarea,
} from '@/components/ui/input-group';
import TaskCategorySelect from './TaskCategorySelect.vue';
import { Label } from '@/components/ui/label';
import DatePicker from '@/components/DatePicker.vue';

// Interface for task details
interface TaskDetails {
  taskName: string;
  taskDescription: string;
  taskCategory: string | null;
  dateDue: Date | null;
}

// hold it in a const so its all together
const taskDetails = ref<TaskDetails>({
  taskName: '',
  taskDescription: '',
  taskCategory: null,
  dateDue: null,
});

// Define an emit for task details so that it can be shown on the creation form
const emit = defineEmits<{
  (e: 'createTask', payload: TaskDetails): void;
}>();

// Emit the task creation
const emitTaskCreation = () => {
  emit('createTask', { ...taskDetails.value });
};
</script>

<template>
  <div class="flex flex-col justify-center items-center align-center gap-10 pt-15 max-w-md mx-auto">
    <h1 class="text-2xl text-green-500">Create Task</h1>
    <InputGroup>
      <InputGroupInput placeholder="Enter task name" type="text" v-model="taskDetails.taskName" />
    </InputGroup>
    <InputGroup>
      <InputGroupTextarea
        placeholder="Enter task description here"
        type="text"
        v-model="taskDetails.taskDescription"
      />
    </InputGroup>

    <TaskCategorySelect
      :task-category="taskDetails.taskCategory"
      @emit-task-category="taskDetails.taskCategory = $event"
    />

    <DatePicker :initial-date="taskDetails.dateDue" @emit-due-date="taskDetails.dateDue = $event" />

    <Button @click="emitTaskCreation">Create Task</Button>
  </div>
</template>
