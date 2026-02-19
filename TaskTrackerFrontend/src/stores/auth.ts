import { defineStore } from 'pinia';
import axios, { AxiosError } from 'axios';

type AuthUser = {
  id: number;
  name: string;
  email: string;
};

type AuthState = {
  user: AuthUser | null;
  token: string | null;
};

export const useAuthStore = defineStore('auth', {
  state: (): AuthState => ({
    user: null,
    token: null,
  }),

  actions: {
    setUser(user: AuthUser | null) {
      this.user = user;
    },

    setToken(token: string | null) {
      this.token = token;
    },

    logout() {
      this.user = null;
      this.token = null;
      localStorage.removeItem('token');
    },

    async fetchUser() {
      if (!this.token) {
        console.log('There is no token, running logout');
        this.logout();
        return;
      }

      try {
        const response = await axios.get<AuthUser>('http://127.0.0.1:8000/api/user', {
          headers: {
            Authorization: `Bearer ${this.token}`,
          },
        });
        this.user = response.data;
      } catch (err) {
        this.logout();
      }
    },
  },
});
