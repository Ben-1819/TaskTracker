<script setup lang="ts">
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';

const props = defineProps<{
  taskCategory: string | null;
}>();
const emit = defineEmits<{
  (e: 'emitTaskCategory', value: string | null): void;
}>();

const taskCategories: string[] = ['Housework', 'Study', 'Family', 'Sports'];

const selectCategory = (category: unknown) => {
  // Do a check to see if the value is either null or string
  if (typeof category === 'string' || category === null) {
    emit('emitTaskCategory', category);
  }
};
</script>

<template>
  <!-- Need to make sure in the emit that it knows that it may be null -->
  <Select :model-value="props.taskCategory" @update:model-value="selectCategory">
    <SelectTrigger>
      <SelectValue placeholder="Select Category" />
    </SelectTrigger>
    <SelectContent>
      <SelectItem v-for="item in taskCategories" :key="item" :value="item">{{ item }}</SelectItem>
    </SelectContent>
  </Select>
</template>
