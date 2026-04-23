<script setup lang="ts">
import { useRouter } from 'vue-router';
import { TrashIcon, EditIcon } from 'lucide-vue-next';
const router = useRouter();

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

defineProps<{
  task: Task;
}>();

/**
 * formatDate function - formats a date object into a more readable string format like 01 Jan 2026.
 * @param date
 */
const formatDate = (date: Date): string => {
  return new Intl.DateTimeFormat('en-GB', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
  }).format(date);
};

/**
 * isOverdue function - checks if a date is before the current date
 * @param date
 */
const isOverdue = (date: Date): boolean => {
  return date < new Date();
};

/**
 * truncateText function - truncates a string so that it isn't too big for the cards
 * @param text
 * @param maxLength
 */
const truncateText = (text: string, maxLength: number): string => {
  return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
};
</script>

<template>
  <div
    class="w-full min-w-0 shadow-lg rounded-lg overflow-hidden border-2 border-solid border-green-600 ml-10 mt-10"
  >
    <div class="p-4">
      <h3 class="text-xl font-semibold">{{ task.name }}</h3>
      <p class="text-green-600">{{ truncateText(task.description, 100) }}</p>
    </div>
    <div class="p-4 flex flex-col text-green-600">
      <p>Category: {{ task.category }}</p>
      <p>Date set: {{ formatDate(task.dateSet) }}</p>
      <p>Date due: {{ formatDate(task.dateDue) }}</p>
      <p>Completed: {{ task.completed ? 'Yes' : 'No' }}</p>
      <p>Overdue: {{ isOverdue(task.dateDue) ? 'Yes' : 'No' }}</p>
    </div>
    <div class="p-4 flex justify-between items-center">
      <TrashIcon />
      <Button variant="outline" class="text-green-600 border-2 border-solid border-green-600 p-2"
        >Complete Task</Button
      >
      <EditIcon />
    </div>
  </div>
</template>
