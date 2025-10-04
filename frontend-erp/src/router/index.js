import { createRouter, createWebHistory } from "vue-router";

// Import pages
import Login from "../auth/Login.vue";
import Dashboard from "../pages/Dashboard.vue";
import Product from "../pages/Product.vue"; 
import Order from "../pages/Order.vue"; 
import Customer from "../pages/Customer.vue";
import Supplier from "../pages/Supplier.vue";

const routes = [
  {
    path: "/login",
    name: "Login",
    component: Login,
  },
  {
    path: "/dashboard",
    name: "Dashboard",
    component: Dashboard,
    meta: { requiresAuth: true },
  },
  {
    path: "/product",
    name: "Product",
    component: Product,
    meta: { requiresAuth: true }, 
  },
  {
    path: "/order",
    name: "Order",
    component: Order,
    meta: { requiresAuth: true },
  },
  {
    path: "/customer",
    name: "Customer",
    component: Customer,
    meta: { requiresAuth: true },
  },
  {
    path: "/supplier",
    name: "Supplier",
    component: Supplier,
    meta: { requiresAuth: true },
  },
  {
    path: "/:pathMatch(.*)*", // fallback untuk 404
    redirect: "/login",
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Route guard
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("token"); // atau vue-cookies
  if (to.meta.requiresAuth && !token) {
    next("/login");
  } else {
    next();
  }
});

export default router;
