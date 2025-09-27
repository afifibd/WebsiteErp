<template>
  <div>
    <!-- Mobile Navbar -->
    <div class="lg:hidden flex items-center justify-between bg-white border-b border-gray-200 p-4">
      <img src="/images/logo.png" alt="Logo" class="w-[110px]" />

      <!-- Hamburger -->
      <button @click="toggleMobileMenu" class="text-gray-900 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>

    <!-- Mobile Menu Overlay -->
    <transition name="fade">
      <div v-if="mobileMenuOpen" class="lg:hidden fixed inset-0 backdrop-blur-sm z-40" @click="mobileMenuOpen=false"></div>
    </transition>

    <transition name="slide">
      <aside v-if="mobileMenuOpen" class="lg:hidden fixed top-0 left-0 w-64 h-full bg-white border-r border-gray-200 z-50 flex flex-col justify-between">
        <!-- Logo -->
        <div>
          <div class="flex justify-start p-4">
            <img src="/images/logo.png" alt="Logo" class="w-[110px]" />
          </div>

          <!-- Navigation -->
          <nav class="mt-4">
            <ul class="space-y-1">
              <li v-for="item in menu" :key="item.label">
                <RouterLink
                  :to="item.route"
                  class="flex items-center gap-3 w-full px-4 py-2.5 text-sm font-medium rounded-lg transition"
                  :class="[
                    $route.path === item.route
                      ? 'bg-gray-100 text-gray-900'
                      : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                  ]"
                  @click="mobileMenuOpen = false"
                >
                  <component :is="item.icon" class="w-5 h-5" />
                  <span>{{ item.label }}</span>
                </RouterLink>
              </li>
            </ul>
          </nav>
        </div>

        <!-- Profile & Logout -->
        <div class="border-t border-gray-200">
          <div class="flex items-center justify-between p-4 hover:bg-gray-50 transition cursor-pointer">
            <RouterLink to="/profile" class="flex items-center gap-3 flex-1">
              <div class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-50">
                <span class="text-blue-600 font-medium">OR</span>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-900">Olivia Rhye</p>
                <p class="text-xs text-gray-500">Admin</p>
              </div>
            </RouterLink>
            <button @click="handleLogout" class="flex items-center justify-center w-9 h-9 rounded-lg hover:text-red-600 text-gray-900 transition">
              <LogOut class="w-6 h-6" />
            </button>
          </div>
        </div>
      </aside>
    </transition>

    <!-- Desktop Sidebar -->
    <aside class="hidden lg:flex h-screen bg-white border-r border-gray-200 flex-col justify-between fixed w-1/6">
      <div>
        <div class="flex justify-start p-4">
          <img src="/images/logo.png" alt="Logo" class="w-[110px]" />
        </div>
        <nav class="mt-4">
          <ul class="space-y-1">
            <li v-for="item in menu" :key="item.label">
              <RouterLink
                :to="item.route"
                class="flex items-center gap-3 w-full px-4 py-2.5 text-sm font-medium rounded-lg transition"
                :class="[
                  $route.path === item.route
                    ? 'bg-gray-100 text-gray-900'
                    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                ]"
              >
                <component :is="item.icon" class="w-5 h-5" />
                <span>{{ item.label }}</span>
              </RouterLink>
            </li>
          </ul>
        </nav>
      </div>

      <!-- Profile & Logout -->
      <div class="border-t border-gray-200">
        <div class="flex items-center justify-between p-4 hover:bg-gray-50 transition cursor-pointer">
          <RouterLink to="/profile" class="flex items-center gap-3 flex-1">
            <div class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-50">
              <span class="text-blue-600 font-medium">OR</span>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-900">Olivia Rhye</p>
              <p class="text-xs text-gray-500">Admin</p>
            </div>
          </RouterLink>
          <button @click="handleLogout" class="flex items-center justify-center w-9 h-9 rounded-lg hover:text-red-600 text-gray-900 transition">
            <LogOut class="w-6 h-6" />
          </button>
        </div>
      </div>
    </aside>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { RouterLink } from "vue-router";
import {
  Home,
  Package,
  ShoppingCart,
  Users,
  Truck,
  PieChart,
  Settings,
  LogOut,
} from "lucide-vue-next";

const menu = [
  { label: "Dashboard", icon: Home, route: "/dashboard" },
  { label: "Products", icon: Package, route: "/product" },
  { label: "Orders", icon: ShoppingCart, route: "/order" },
  { label: "Customers", icon: Users, route: "/customer" },
  { label: "Suppliers", icon: Truck, route: "/supplier" },
  { label: "Reports", icon: PieChart, route: "/report" },
  { label: "Settings", icon: Settings, route: "/setting" },
];

const mobileMenuOpen = ref(false);
function toggleMobileMenu() {
  mobileMenuOpen.value = !mobileMenuOpen.value;
}

function handleLogout() {
  console.log("Logout clicked");
}
</script>

<style>
/* Transition untuk menu mobile */
.slide-enter-active, .slide-leave-active {
  transition: transform 0.3s ease;
}
.slide-enter-from {
  transform: translateX(-100%);
}
.slide-enter-to {
  transform: translateX(0);
}
.slide-leave-from {
  transform: translateX(0);
}
.slide-leave-to {
  transform: translateX(-100%);
}
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
.fade-enter-to, .fade-leave-from {
  opacity: 1;
}
</style>
