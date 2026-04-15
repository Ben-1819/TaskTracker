<script setup lang="ts">
import {
  CalendarDate,
  DateFormatter,
  getLocalTimeZone,
  today,
  type DateValue,
} from '@internationalized/date';
import { shallowRef, watch } from 'vue';
import { CalendarIcon } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { cn } from '@/lib/utils';

const props = defineProps<{
  initialDate: Date | null;
}>();

const emit = defineEmits<{
  (e: 'emitDueDate', value: Date | null): void;
}>();

const formatter = new DateFormatter('en-GB', {
  dateStyle: 'long',
});

const defaultPlaceholder = today(getLocalTimeZone());

const toCalendarDate = (value: Date | null): DateValue | undefined => {
  if (!value) return undefined;

  return new CalendarDate(value.getFullYear(), value.getMonth() + 1, value.getDate());
};

const date = shallowRef<DateValue | undefined>(toCalendarDate(props.initialDate));

watch(
  () => props.initialDate,
  (newInitialDate) => {
    date.value = toCalendarDate(newInitialDate);
  },
);

const handleDateChange = (value: DateValue | undefined) => {
  date.value = value;

  if (value) {
    emit('emitDueDate', value.toDate(getLocalTimeZone()));
  }
};
</script>

<template>
  <Popover>
    <PopoverTrigger as-child>
      <Button
        variant="outline"
        :class="
          cn('w-[280px] justify-start text-left font-normal', !date && 'text-muted-foreground')
        "
      >
        <CalendarIcon class="mr-2 h-4 w-4" />
        {{ date ? formatter.format(date.toDate(getLocalTimeZone())) : 'Select a due date' }}
      </Button>
    </PopoverTrigger>

    <PopoverContent class="w-auto p-0" align-start>
      <Calendar
        :model-value="date"
        :placeholder="date"
        @update:model-value="handleDateChange"
        :initial-focus="true"
        layout="month-and-year"
      />
    </PopoverContent>
  </Popover>
</template>
