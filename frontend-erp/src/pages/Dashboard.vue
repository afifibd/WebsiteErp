<template>
  <div class="flex flex-col lg:flex-row min-h-screen bg-white">
    <!-- Sidebar -->
    <Sidebar class="w-full lg:w-1/6" />

    <!-- Main content -->
    <div class="flex-1 flex flex-col p-4 mpx-4 md:py-6 gap-6">
      
      <!-- Page Header -->
      <PageHeader
        title="Dashboard Overview"
        subtitle="Welcome to your wholesale management system"
        :show-button="false"
      />

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-8">
        <MyCard
          v-for="stat in stats"
          :key="stat.label"
          class="flex items-center space-x-4 hover:shadow-lg transition"
        >
          <component :is="stat.icon" class="w-8 h-8 text-blue-500" />
          <div>
            <p class="text-gray-500 text-sm">{{ stat.label }}</p>
            <p class="text-xl font-semibold">{{ stat.value }}</p>
          </div>
        </MyCard>
      </div>

      <!-- Recent Orders Table -->
      <MyCard title="Recent Orders">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse min-w-[600px]">
            <thead>
              <tr class="border-b">
                <th class="py-2 px-3">Order ID</th>
                <th class="py-2 px-3">Customer</th>
                <th class="py-2 px-3">Status</th>
                <th class="py-2 px-3">Total</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="order in orders"
                :key="order.id"
                class="border-b hover:bg-gray-50"
              >
                <td class="py-2 px-3">{{ order.id }}</td>
                <td class="py-2 px-3">{{ order.customer }}</td>
                <td class="py-2 px-3">
                  <span
                    class="px-2 py-1 rounded text-xs font-medium"
                    :class="{
                      'bg-green-100 text-green-600': order.status === 'Completed',
                      'bg-yellow-100 text-yellow-600': order.status === 'Pending',
                      'bg-red-100 text-red-600': order.status === 'Cancelled',
                    }"
                  >
                    {{ order.status }}
                  </span>
                </td>
                <td class="py-2 px-3">Rp {{ order.total.toLocaleString() }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </MyCard>
    </div>
  </div>
</template>

<script setup>
import { Package, Truck, ShoppingCart } from "lucide-vue-next";
import Sidebar from "../components/Sidebar.vue";
import MyCard from "../components/MyCard.vue";
import PageHeader from "../components/PageHeader.vue";

const stats = [
  { label: "Products", value: 120, icon: Package },
  { label: "Suppliers", value: 35, icon: Truck },
  { label: "Orders", value: 245, icon: ShoppingCart },
];

const orders = [
  { id: "ORD-001", customer: "John Doe", status: "Completed", total: 250000 },
  { id: "ORD-002", customer: "Jane Smith", status: "Pending", total: 150000 },
  { id: "ORD-003", customer: "PT Abadi Jaya", status: "Cancelled", total: 500000 },
];
</script>
