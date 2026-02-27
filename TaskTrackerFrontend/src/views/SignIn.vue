<script setup lang="ts">
import { ref, reactive } from 'vue';
import axios from 'axios';
import { useAuthStore } from '@/stores/auth';
import { Button } from '@/components/ui/button';
import { MailIcon, KeyIcon } from 'lucide-vue-next';
import { InputGroup, InputGroupAddon, InputGroupInput } from '@/components/ui/input-group';

const authStore = useAuthStore();

interface LoginDetails {
  email: string;
  password: string;
}

type ValidationErrors = Record<string, string[]>;

interface LoginResponse {
  token: string;
  user: unknown;
}

interface Laravel422Response {
  errors: ValidationErrors;
}

interface Laravel401Response {
  error: string;
}

const validationErrors = ref<ValidationErrors>({});
const credentialError = ref<string | null>(null);

const credentialErrors = ref({});

const loginDetails = reactive<LoginDetails>({
  email: '',
  password: '',
});

const attemptLogin = async (): Promise<void> => {
  console.log(`Email ${loginDetails.email}`);
  console.log(`Password ${loginDetails.password}`);
  try {
    const response = await axios
      .post('http://127.0.0.1:8000/api/login', {
        email: loginDetails.email,
        password: loginDetails.password,
      })
      .then((response) => {
        const token = response.data.token;
        const user = response.data.user;
        authStore.setUser(response.data.user);
        authStore.setToken(token);
        localStorage.setItem('token', token);
        authStore.fetchUser();
      });
  } catch (error: unknown) {
    console.error(`[LOGIN-ERROR] An error has occurred ${error}`);

    if (!axios.isAxiosError(error) || !error.response) {
      credentialError.value = 'Login failed. Please try again';
      return;
    }

    const status = error.response.status;

    if (status === 422) {
      const data = error.response.data as Laravel422Response;
      validationErrors.value = data.errors ?? {};
      return;
    }

    if (status == 401) {
      const data = error.response.data as Laravel401Response;
      credentialError.value = data.error ?? 'Invalid email or password.';
      return;
    }

    credentialError.value = 'Login failed. Please try again.';
  }
};

const clearFieldError = (field: string): void => {
  if (validationErrors.value[field]) {
    delete validationErrors.value[field];
    credentialError.value = null;
  }
};
</script>

<template>
  <div class="grid w-full max-w-sm gap-6">
    <InputGroup>
      <InputGroupInput
        type="email"
        v-model="loginDetails.email"
        placeholder="Enter your email here"
        @input="clearFieldError"
      />
      <InputGroupAddon>
        <MailIcon />
      </InputGroupAddon>
    </InputGroup>
    <InputGroup>
      <InputGroupInput
        type="password"
        v-model="loginDetails.password"
        placeholder="Enter your password here"
        @input="clearFieldError"
      />
      <InputGroupAddon>
        <KeyIcon />
      </InputGroupAddon>
    </InputGroup>
    <Button variant="default" type="submit" @click="attemptLogin">Log in</Button>
  </div>
</template>
