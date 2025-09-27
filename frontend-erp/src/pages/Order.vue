<script setup>
import { ref, computed } from "vue";
import Sidebar from "../components/Sidebar.vue";
import PageHeader from "../components/PageHeader.vue";
import MyCard from "../components/MyCard.vue";
import MyInputField from "../components/MyInputField.vue";
import MyDropdown from "../components/MyDropdown.vue";
import MyTable from "../components/MyTable.vue";
import StatusBadge from "../components/StatusBadge.vue";
import Slideout from "../components/Slideout.vue";
import MyButton from "../components/MyButton.vue";
import {
  Search,
  Filter,
  ShoppingCart,
  Clock,
  Truck,
  Coins,
  Pencil,
  Trash,
  Plus,
} from "lucide-vue-next";

// =============================
// State Filters
// =============================
const search = ref("");
const payment = ref("");
const status = ref("");

const payments = [
  { label: "All Payment", value: "" },
  { label: "Paid", value: "paid" },
  { label: "Pending", value: "pending" },
];

const statuses = [
  { label: "All Status", value: "" },
  { label: "Processing", value: "processing" },
  { label: "Shipped", value: "shipped" },
  { label: "Delivered", value: "delivered" },
  { label: "Pending", value: "pending" },
];

// =============================
// Dummy Orders
// =============================
const orders = ref([
  {
    id: 1,
    code: "ORD-001",
    customer: "ABC Mart",
    email: "orders@abcmart.com",
    date: "2024-09-20",
    items: 15,
    total: 38750,
    payment: "paid",
    status: "shipped",
  },
  {
    id: 2,
    code: "ORD-002",
    customer: "QuickStop Store",
    email: "purchasing@quickstop.com",
    date: "2024-09-19",
    items: 123,
    total: 58125,
    payment: "pending",
    status: "processing",
  },
  {
    id: 3,
    code: "ORD-003",
    customer: "Retail Hub",
    email: "orders@retailhub.com",
    date: "2024-09-18",
    items: 50,
    total: 150000,
    payment: "paid",
    status: "delivered",
  },
  {
    id: 4,
    code: "ORD-004",
    customer: "Global Supplies",
    email: "orders@globalsupplies.com",
    date: "2024-09-17",
    items: 75,
    total: 250000,
    payment: "paid",
    status: "processing",
  },
  {
    id: 5,
    code: "ORD-005",
    customer: "Wholesale Direct",
    email: "orders@wholesaledirect.com",
    date: "2024-09-16",
    items: 100,
    total: 500000,
    payment: "paid",
    status: "delivered",
  },
]);

// =============================
// Computed: Filter Orders
// =============================
const filteredOrders = computed(() => {
  return orders.value.filter((order) => {
    const matchesSearch =
      search.value === "" ||
      order.customer.toLowerCase().includes(search.value.toLowerCase()) ||
      order.code.toLowerCase().includes(search.value.toLowerCase());

    const matchesPayment =
      payment.value === "" || order.payment === payment.value;

    const matchesStatus = status.value === "" || order.status === status.value;

    return matchesSearch && matchesPayment && matchesStatus;
  });
});

// =============================
// Slideout Logic
// =============================
const showSlideout = ref(false);
const slideoutMode = ref("add"); // "add" | "edit" | "delete"
const currentOrder = ref(null);

const form = ref({
  code: "",
  customer: "",
  email: "",
  date: "",
  items: 0,
  total: 0,
  payment: "",
  status: "",
});

// Open Add Slideout
function openAdd() {
  slideoutMode.value = "add";
  form.value = {
    code: "",
    customer: "",
    email: "",
    date: "",
    items: 0,
    total: 0,
    payment: "",
    status: "",
  };
  showSlideout.value = true;
}

// Open Edit Slideout
function openEdit(order) {
  slideoutMode.value = "edit";
  form.value = { ...order };
  currentOrder.value = order;
  showSlideout.value = true;
}

// Open Delete Slideout
function openDelete(order) {
  slideoutMode.value = "delete";
  currentOrder.value = order;
  showSlideout.value = true;
}

// Close Slideout
function closeSlideout() {
  showSlideout.value = false;
}

// =============================
// CRUD Dummy
// =============================
function saveOrder() {
  const newOrder = {
    ...form.value,
    id: Date.now(),
    total: Number(form.value.total),
  };
  orders.value.push(newOrder);
  showSlideout.value = false;
}

function updateOrder() {
  const index = orders.value.findIndex((o) => o.id === currentOrder.value.id);
  if (index !== -1) {
    orders.value[index] = { ...form.value };
  }
  showSlideout.value = false;
}

function deleteOrder() {
  orders.value = orders.value.filter((o) => o.id !== currentOrder.value.id);
  showSlideout.value = false;
}

// =============================
// Helpers
// =============================
function formatCurrency(value) {
  return "Rp" + Number(value).toLocaleString();
}
</script>

<template>
  <div class="relative">
    <div class="flex flex-col lg:flex-row min-h-screen bg-white">
      <!-- Sidebar -->
      <Sidebar class="w-full lg:w-1/6" />

      <!-- Main Content -->
      <div class="flex-1 flex flex-col p-4 md:px-4 md:py-6 gap-6">
        <!-- Header -->
        <PageHeader
          title="Order Management"
          subtitle="Track and manage wholesale orders"
          buttonText="New Order"
          buttonIcon="plus"
          @button-click="openAdd"
        />

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <MyCard>
            <div class="flex justify-between items-center">
              <div>
                <p class="text-sm text-gray-500">Total Orders</p>
                <p class="text-lg font-semibold">{{ orders.length }}</p>
              </div>
              <ShoppingCart class="w-10 h-10 text-gray-800" />
            </div>
          </MyCard>
          <MyCard>
            <div class="flex justify-between items-center">
              <div>
                <p class="text-sm text-gray-500">Processing</p>
                <p class="text-lg font-semibold">
                  {{ orders.filter((o) => o.status === "processing").length }}
                </p>
              </div>
              <Clock class="w-10 h-10 text-gray-800" />
            </div>
          </MyCard>
          <MyCard>
            <div class="flex justify-between items-center">
              <div>
                <p class="text-sm text-gray-500">Shipped</p>
                <p class="text-lg font-semibold">
                  {{ orders.filter((o) => o.status === "shipped").length }}
                </p>
              </div>
              <Truck class="w-10 h-10 text-gray-800" />
            </div>
          </MyCard>
          <MyCard>
            <div class="flex justify-between items-center">
              <div>
                <p class="text-sm text-gray-500">Revenue</p>
                <p class="text-lg font-semibold">
                  {{
                    formatCurrency(orders.reduce((acc, o) => acc + o.total, 0))
                  }}
                </p>
              </div>
              <Coins class="w-10 h-10 text-gray-800" />
            </div>
          </MyCard>
        </div>

        <!-- Filters -->
        <MyCard padding="p-4" title="Filters">
          <template #icon>
            <Filter class="w-4 h-4 text-gray-900" />
          </template>
          <div class="flex flex-wrap gap-4">
            <MyInputField
              v-model="search"
              placeholder="Search"
              class="flex-3 min-w-[200px]"
            />
            <MyDropdown
              v-model="payment"
              :options="payments"
              placeholder="All Payment"
              class="flex-1 min-w-[150px]"
            />
            <MyDropdown
              v-model="status"
              :options="statuses"
              placeholder="All Status"
              class="flex-1 min-w-[150px]"
            />
          </div>
        </MyCard>

        <!-- Orders Table -->
        <div class="overflow-x-auto">
          <MyTable
            title="Orders"
            :count="filteredOrders.length"
            :items="filteredOrders"
            :page-size="5"
          >
            <template #icon>
              <ShoppingCart class="w-5 h-5 text-gray-900" />
            </template>
            <template #header>
              <th class="px-6 py-3 font-medium">Order ID</th>
              <th class="px-6 py-3 font-medium">Customer</th>
              <th class="px-6 py-3 font-medium">Date</th>
              <th class="px-6 py-3 font-medium">Items</th>
              <th class="px-6 py-3 font-medium">Total</th>
              <th class="px-6 py-3 font-medium">Payment</th>
              <th class="px-6 py-3 font-medium">Status</th>
              <th class="px-6 py-3 font-medium">Actions</th>
            </template>

            <template #body="{ items }">
              <tr v-for="(order, i) in items" :key="i">
                <td class="px-6 py-4">{{ order.code }}</td>
                <td class="px-6 py-4">
                  <div class="flex flex-col">
                    <span class="font-medium">{{ order.customer }}</span>
                    <span class="text-sm text-gray-500">{{ order.email }}</span>
                  </div>
                </td>
                <td class="px-6 py-4">{{ order.date }}</td>
                <td class="px-6 py-4">{{ order.items }} items</td>
                <td class="px-6 py-4">{{ formatCurrency(order.total) }}</td>
                <td class="px-6 py-4">
                  <StatusBadge :status="order.payment">
                    {{ order.payment === "paid" ? "Paid" : "Pending" }}
                  </StatusBadge>
                </td>
                <td class="px-6 py-4">
                  <StatusBadge :status="order.status">{{
                    order.status
                  }}</StatusBadge>
                </td>
                <td class="px-6 py-4 flex gap-2 flex-wrap">
                  <button
                    class="text-blue-600 hover:text-blue-800"
                    @click="openEdit(order)"
                  >
                    <Pencil class="w-4 h-4" />
                  </button>
                  <button
                    class="text-red-600 hover:text-red-800"
                    @click="openDelete(order)"
                  >
                    <Trash class="w-4 h-4" />
                  </button>
                </td>
              </tr>

              <tr v-if="items.length === 0">
                <td colspan="8" class="text-center py-4 text-gray-500">
                  No orders found.
                </td>
              </tr>
            </template>
          </MyTable>
        </div>
      </div>
    </div>

    <!-- Slideout -->
    <Slideout
      :open="showSlideout"
      :title="
        slideoutMode === 'add'
          ? 'Add Order'
          : slideoutMode === 'edit'
          ? 'Edit Order'
          : 'Delete Order'
      "
      @close="showSlideout = false"
    >
      <!-- Form Add / Edit -->
      <template v-if="slideoutMode === 'add' || slideoutMode === 'edit'">
        <form class="flex flex-col gap-4">
          <MyInputField
            label="Order Code"
            v-model="form.code"
            placeholder="ORD-XXX"
          />
          <MyInputField
            label="Customer"
            v-model="form.customer"
            placeholder="Customer Name"
          />
          <MyInputField
            label="Email"
            v-model="form.email"
            placeholder="Email Address"
          />
          <MyInputField label="Date" v-model="form.date" type="date" />
          <MyInputField
            label="Items"
            v-model="form.items"
            type="number"
            placeholder="Items"
          />
          <MyInputField
            label="Total"
            v-model="form.total"
            type="number"
            placeholder="Total Amount"
          />
          <MyDropdown
            label="Payment"
            v-model="form.payment"
            :options="payments.slice(1)"
            placeholder="Select Payment"
          />
          <MyDropdown
            label="Status"
            v-model="form.status"
            :options="statuses.slice(1)"
            placeholder="Select Status"
          />
        </form>
      </template>

      <!-- Delete Confirmation -->
      <template v-else-if="slideoutMode === 'delete'">
        <p class="text-gray-700">
          Are you sure you want to delete
          <span class="font-semibold">{{ currentOrder?.code }}</span
          >?
        </p>
      </template>

      <!-- Footer Buttons -->
      <template #footer>
        <div class="flex md:flex-row gap-2">
          <MyButton
            color="secondary"
            variant="filled"
            size="md"
            class="w-full md:w-auto"
            @click="closeSlideout"
          >
            Cancel
          </MyButton>

          <MyButton
            v-if="slideoutMode === 'add'"
            color="success"
            variant="filled"
            size="md"
            class="w-full md:w-auto"
            @click="saveOrder"
          >
            Submit
          </MyButton>

          <MyButton
            v-else-if="slideoutMode === 'edit'"
            color="primary"
            variant="filled"
            size="md"
            class="w-full md:w-auto"
            @click="updateOrder"
          >
            Update
          </MyButton>

          <MyButton
            v-else-if="slideoutMode === 'delete'"
            color="error"
            variant="filled"
            size="md"
            class="w-full md:w-auto"
            @click="deleteOrder"
          >
            Delete
          </MyButton>
        </div>
      </template>
    </Slideout>
  </div>
</template>
