<script setup>
import { ref } from "vue";
import axios from "axios";

// State untuk email, password, dan loading
const email = ref("");
const password = ref("");
const remember = ref(false);
const isLoading = ref(false);

// Set base URL untuk axios
axios.defaults.baseURL = "http://localhost:8000";
axios.defaults.withCredentials = true;

const handleLogin = async () => {
  // Validasi input
  if (!email.value || !password.value) {
    alert("Email dan password tidak boleh kosong!");
    return;
  }

  isLoading.value = true;

  try {
    // Dapatkan CSRF token
    await axios.get("/sanctum/csrf-cookie");

    // Kirim permintaan login
    const response = await axios.post("/api/login", {
      email: email.value,
      password: password.value,
      remember: remember.value, // Kirim data remember
    });

    // Simpan token di localStorage
    localStorage.setItem("token", response.data.token);

    alert("Login berhasil");
    window.location.href = "/dashboard"; // Redirect ke dashboard
  } catch (error) {
    console.error(error.response?.data || error.message);
    if (error.response && error.response.data && error.response.data.message) {
      alert(error.response.data.message);
    } else {
      alert("Login gagal, cek email & password!");
    }
  } finally {
    isLoading.value = false;
  }
};
</script>
<template>
  <div class="flex flex-col min-h-screen lg:flex-row">

    <div class="flex-1 flex flex-col bg-white">
      <div class="flex justify-start p-4">
        <img src="/images/logo.png" alt="Logo" class="w-[110px]" />
      </div>

      <div
        class="flex-1 flex flex-col justify-center gap-8 max-w-sm w-full mx-auto px-4 sm:px-6 lg:px-8"
      >
        <div class="flex flex-col gap-3 text-center lg:text-left">
          <h1 class="text-3xl sm:text-4xl font-semibold text-gray-900">
            Log in
          </h1>
          <p class="text-sm sm:text-base font-normal text-gray-600">
            Welcome back! Please enter your details.
          </p>
        </div>

        <form @submit.prevent="handleLogin" class="flex flex-col gap-4 w-full">
          <div class="flex flex-col gap-4">
            <div class="flex flex-col gap-1.5">
              <label class="text-gray-700 font-medium text-sm"
                >Email</label
              >
              <input
                type="email"
                v-model="email"
                placeholder="Enter your email"
                class="border border-gray-300 rounded-lg px-4 py-2 text-base focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            <div class="flex flex-col gap-1.5">
              <label class="text-gray-700 font-medium text-sm"
                >Password</label
              >
              <input
                type="password"
                v-model="password"
                placeholder="Enter your password"
                class="border border-gray-300 rounded-lg px-4 py-2 text-base focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
          </div>

          <div class="flex flex-col sm:flex-row sm:items-center justify-between">
            <label
              class="flex items-center gap-1 text-sm text-gray-700 font-medium"
            >
              <input type="checkbox" v-model="remember" class="accent-blue-500" />
              Remember for 30 days
            </label>
            <a href="#" class="text-blue-600 hover:underline text-sm font-medium">
              Forgot password?
            </a>
          </div>

          <button
            type="submit"
            :disabled="isLoading"
            class="bg-blue-600 text-white rounded-lg py-2 mt-2 hover:bg-blue-700 transition w-full font-medium"
          >
            <span v-if="isLoading">Loading...</span>
            <span v-else>Sign in</span>
          </button>
        </form>

        <p class="text-center text-gray-500 text-sm">
          Don't have an account?
          <a href="#" class="text-blue-600 hover:underline">Sign up</a>
        </p>
      </div>

      <div class="flex justify-center p-4">
        <p class="text-center text-gray-600 text-xs">
          © 2025 Afif Ibadurrahman. All rights reserved.
        </p>
      </div>
    </div>

    <div
      class="relative hidden lg:block flex-1 bg-cover bg-center rounded-l-[40px] overflow-hidden"
      style="background-image: url('/images/bg-image.jpg')"
    >
      <div
        class="absolute inset-0 bg-black/40 flex flex-col justify-end p-10 text-white"
      >
        <h2 class="text-2xl sm:text-3xl font-bold mb-2">
          Business Integration, One System
        </h2>
        <p class="text-lg sm:text-xl font-semibold">NovaERP Solutions</p>
      </div>
    </div>
  </div>
</template>

