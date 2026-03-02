<script setup lang="ts">
import { ref, reactive } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { Button } from '@/components/ui/button';
import { MailIcon, KeyIcon } from 'lucide-vue-next';
import { InputGroup, InputGroupAddon, InputGroupInput } from '@/components/ui/input-group';
import { Label } from '@/components/ui/label';

const router = useRouter();
const authStore = useAuthStore();

interface UserDetails {
  firstName: string;
  lastName: string;
  email: string;
  password: string;
}

type ValidationErrors = Record<string, string[]>;

type ErrorCode = number;

interface Laravel422Response {
  errors: ValidationErrors;
}

const validationErrors = ref<ValidationErrors>({});

const userDetails = reactive<UserDetails>({
  firstName: '',
  lastName: '',
  email: '',
  password: '',
});

const registerAttempt = async (): Promise<void> => {
  console.log(`First name= ${userDetails.firstName}`);
  console.log(`Last name= ${userDetails.lastName}`);
  console.log(`email= ${userDetails.email}`);
  console.log(`password= ${userDetails.password}`);

  try {
    const response = await axios
      .post('http://127.0.0.1:8000/api/register', {
        first_name: userDetails.firstName,
        last_name: userDetails.lastName,
        email: userDetails.email,
        password: userDetails.password,
      })
      .then((response) => {
        const token = response.data.token;
        localStorage.setItem('token', token);
        authStore.setUser(response.data.user);
        authStore.setToken(token);
        goToHome();
      });
  } catch (error: unknown) {
    console.error(`[LOGIN-ERROR] A login error has occurred: ${error}`);

    if (!axios.isAxiosError(error) || !error.response) {
      return;
    }

    const status = error.response.status;

    if (status === 422) {
      const data = error.response.data as Laravel422Response;
      validationErrors.value = data.errors ?? {};
      return;
    }
  }
};

const clearFieldError = (): void => {
  validationErrors.value = {};
};

const goToLogin = (): void => {
  router.push({
    name: 'SignIn',
  });
};

const goToHome = (): void => {
  router.push({
    name: 'Home',
  });
};
</script>

<template>
  <div class="pt-5 text-center">
    <h1 class="text-2xl text-green-500">Register</h1>
  </div>
  <div class="flex flex-col justify-center items-center align-center gap-10 pt-15 max-w-md mx-auto">
    <InputGroup>
      <InputGroupInput
        type="text"
        name="firstNameInput"
        id="firstNameInput"
        v-model="userDetails.firstName"
        placeholder="Enter your first name here"
        @input="clearFieldError"
      />
      <Label for="firstNameInput" v-if="validationErrors.firstName">
        {{ validationErrors.firstName }}
      </Label>
    </InputGroup>
    <InputGroup>
      <InputGroupInput
        type="text"
        name="lastNameInput"
        id="lastNameInput"
        v-model="userDetails.lastName"
        placeholder="Enter your last name here"
        @input="clearFieldError"
      />
      <Label for="lastNameInput" v-if="validationErrors.lastName">
        {{ validationErrors.lastName }}
      </Label>
    </InputGroup>
    <InputGroup>
      <InputGroupInput
        type="email"
        name="emailInput"
        id="emailInput"
        v-model="userDetails.email"
        placeholder="Enter your email here"
        @input="clearFieldError"
      />
      <InputGroupAddon>
        <MailIcon />
      </InputGroupAddon>
      <Label for="emailInput" v-if="validationErrors.email">
        {{ validationErrors.email }}
      </Label>
    </InputGroup>
    <InputGroup>
      <InputGroupInput
        type="password"
        name="passwordInput"
        id="passwordInput"
        v-model="userDetails.password"
        placeholder="Enter your password here"
        @input="clearFieldError"
      />
      <InputGroupAddon>
        <KeyIcon />
      </InputGroupAddon>
    </InputGroup>
    <div class="flex flex-row gap-5">
      <Button
        variant="outline"
        size="default"
        class="text-green-600 max-w-sm"
        @click="registerAttempt"
        >Register</Button
      >
      <Button variant="outline" size="default" class="text-green-600 max-w-sm" @click="goToLogin">
        Already have an account?
      </Button>
    </div>
  </div>
</template>
