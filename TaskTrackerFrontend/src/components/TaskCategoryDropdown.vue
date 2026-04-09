<script setup lang="ts">
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

const props = defineProps<{
  modelValue: string | null;
}>();

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void;
}>();

const taskCategories: string[] = ['Housework', 'Study', 'Family', 'Sports'];

const selectCategory = (category: string) => {
  emit('update:modelValue', category);
};
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger>{{ props.modelValue || 'Select Category' }}</DropdownMenuTrigger>
    <DropdownMenuContent>
      <DropdownMenuLabel>Select a task category</DropdownMenuLabel>
      <DropdownMenuSeparator />
      <DropdownMenuItem v-for="item in taskCategories" :key="item" @click="selectCategory(item)">{{
        item
      }}</DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
