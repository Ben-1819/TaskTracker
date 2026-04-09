<script setup lang="ts">
import { Button } from '@/components/ui/button';
</script>
<template>
  <Button variant="outline" class="text-green-600">
    <slot></slot>
  </Button>
</template>
