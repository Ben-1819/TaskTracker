<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import {
  Sidebar,
  SidebarContent,
  SidebarGroup,
  SidebarGroupContent,
  SidebarGroupLabel,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
  SidebarHeader,
  SidebarFooter,
} from '@/components/ui/sidebar';
import { HomeIcon, NotebookIcon, NotebookPenIcon, UserIcon } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

const router = useRouter();

const props = defineProps<{
  firstName: string | undefined;
  lastName: string | undefined;
}>();

const emit = defineEmits<{
  (e: 'logoutEvent'): void;
}>();

interface SidebarItems {
  title: string;
  name: string;
  icon: unknown;
}

const sidebarItems = ref<SidebarItems[]>([
  {
    title: 'Profile',
    name: 'Profile',
    icon: UserIcon,
  },
  {
    title: 'Home',
    name: 'Home',
    icon: HomeIcon,
  },
  {
    title: 'Tasks',
    name: 'Tasks',
    icon: NotebookIcon,
  },
  {
    title: 'Create Task',
    name: 'CreateTask',
    icon: NotebookPenIcon,
  },
]);
</script>

<template>
  <Sidebar>
    <SidebarHeader>
      <div class="flex justify-center">
        <span class="font-semibold"> {{ firstName }} {{ lastName }}</span>
      </div>
    </SidebarHeader>
    <SidebarContent>
      <SidebarGroup>
        <SidebarGroupLabel>Links</SidebarGroupLabel>
      </SidebarGroup>
      <SidebarGroupContent>
        <SidebarMenu>
          <SidebarMenuItem v-for="sidebarItem in sidebarItems" :key="sidebarItem.title">
            <SidebarMenuButton as-child>
              <a @click.prevent="router.push({ name: sidebarItem.name })">
                <component :is="sidebarItem.icon" />
                <span>{{ sidebarItem.title }}</span>
              </a>
            </SidebarMenuButton>
          </SidebarMenuItem>
        </SidebarMenu>
      </SidebarGroupContent>
    </SidebarContent>
    <SidebarFooter>
      <div class="flex justify-center">
        <Button variant="outline" class="text-green-600" @click="emit('logoutEvent')">
          Logout
        </Button>
      </div>
    </SidebarFooter>
  </Sidebar>
</template>
