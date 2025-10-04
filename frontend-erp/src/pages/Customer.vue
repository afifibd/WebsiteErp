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
  Filter,
  User,
  UserCheckIcon,
  Coins,
  Pencil,
  Trash,
  Plus,
  BarChart2,
} from "lucide-vue-next";

// =============================
// State Filters
// =============================
const search = ref("");
const type = ref("");
const status = ref("");

const types = [
  { label: "All Type", value: "" },
  { label: "Retail", value: "retail" },
  { label: "Wholesale", value: "wholesale" },
];

const statuses = [
  { label: "All Status", value: "" },
  { label: "Active", value: "active" },
  { label: "Inactive", value: "inactive" },
];

// =============================
// Dummy Customers
// =============================
const customers = ref([
  {
    id: 1,
    customer: "ABC Mart",
    code: "CUST-001",
    email: "customers@abcmart.com",
    telp: "0812-1111-1111",
    type: "retail",
    orders: 1,
    total_spent: 38750,
    credit_limit: 1000000,
    status: "active",
  },
  {
    id: 2,
    customer: "QuickStop Store",
    code: "CUST-002",
    email: "purchasing@quickstop.com",
    telp: "0812-2222-2222",
    type: "retail",
    orders: 1,
    total_spent: 58125,
    credit_limit: 2000000,
    status: "inactive",
  },
  {
    id: 3,
    customer: "Retail Hub",
    code: "CUST-003",
    email: "customers@retailhub.com",
    telp: "0812-3333-3333",
    type: "wholesale",
    orders: 1,
    total_spent: 150000,
    credit_limit: 5000000,
    status: "inactive",
  },
  {
    id: 4,
    customer: "Global Supplies",
    code: "CUST-004",
    email: "customers@globalsupplies.com",
    telp: "0812-4444-4444",
    type: "wholesale",
    orders: 1,
    total_spent: 250000,
    credit_limit: 10000000,
    status: "active",
  },
  {
    id: 5,
    customer: "Wholesale Direct",
    code: "CUST-005",
    email: "customers@wholesaledirect.com",
    telp: "0812-5555-5555",
    type: "wholesale",
    orders: 1,
    total_spent: 500000,
    credit_limit: 8000000,
    status: "active",
  },
]);

// =============================
// Computed
// =============================
const filteredCustomers = computed(() => {
  return customers.value.filter((customer) => {
    const matchesSearch =
      search.value === "" ||
      customer.customer.toLowerCase().includes(search.value.toLowerCase()) ||
      customer.code.toLowerCase().includes(search.value.toLowerCase());

    const matchesType =
      type.value === "" || customer.type === type.value;

    const matchesStatus =
      status.value === "" || customer.status === status.value;

    return matchesSearch && matchesType && matchesStatus;
  });
});

const revenue = computed(() => {
  return customers.value.reduce((acc, c) => acc + c.total_spent, 0);
});

const avgOrderValue = computed(() => {
  const totalSpent = customers.value.reduce((acc, c) => acc + c.total_spent, 0);
  const totalOrders = customers.value.reduce((acc, c) => acc + c.orders, 0);
  return totalOrders > 0 ? totalSpent / totalOrders : 0;
});

// =============================
// Slideout Logic
// =============================
const showSlideout = ref(false);
const slideoutMode = ref("add"); // "add" | "edit" | "delete"
const currentCustomer = ref(null);

const form = ref({
  code: "",
  customer: "",
  email: "",
  telp: "",
  type: "",
  orders: 0,
  total_spent: 0,
  credit_limit: 0,
  status: "",
});

// Open Add
function openAdd() {
  slideoutMode.value = "add";
  form.value = {
    code: "",
    customer: "",
    email: "",
    telp: "",
    type: "",
    orders: 0,
    total_spent: 0,
    credit_limit: 0,
    status: "",
  };
  showSlideout.value = true;
}

// Open Edit
function openEdit(customer) {
  slideoutMode.value = "edit";
  form.value = { ...customer };
  currentCustomer.value = customer;
  showSlideout.value = true;
}

// Open Delete
function openDelete(customer) {
  slideoutMode.value = "delete";
  currentCustomer.value = customer;
  showSlideout.value = true;
}

// Close
function closeSlideout() {
  showSlideout.value = false;
}

// =============================
// CRUD Dummy
// =============================
function saveCustomer() {
  const newCustomer = {
    ...form.value,
    id: Date.now(),
    orders: Number(form.value.orders),
    total_spent: Number(form.value.total_spent),
    credit_limit: Number(form.value.credit_limit),
  };
  customers.value.push(newCustomer);
  showSlideout.value = false;
}

function updateCustomer() {
  const index = customers.value.findIndex(
    (c) => c.id === currentCustomer.value.id
  );
  if (index !== -1) {
    customers.value[index] = {
      ...form.value,
      orders: Number(form.value.orders),
      total_spent: Number(form.value.total_spent),
      credit_limit: Number(form.value.credit_limit),
    };
  }
  showSlideout.value = false;
}

function deleteCustomer() {
  customers.value = customers.value.filter(
    (c) => c.id !== currentCustomer.value.id
  );
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
          title="Customer Management"
          subtitle="Manage your wholesale customers"
          buttonText="Add Customer"
          buttonIcon="plus"
          @button-click="openAdd"
        />

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <MyCard>
            <div class="flex justify-between items-center">
              <div>
                <p class="text-sm text-gray-500">Total Customers</p>
                <p class="text-lg font-semibold">{{ customers.length }}</p>
              </div>
              <User class="w-10 h-10 text-gray-800" />
            </div>
          </MyCard>
          <MyCard>
            <div class="flex justify-between items-center">
              <div>
                <p class="text-sm text-gray-500">Active Customers</p>
                <p class="text-lg font-semibold">
                  {{ customers.filter((o) => o.status === "active").length }}
                </p>
              </div>
              <UserCheckIcon class="w-10 h-10 text-gray-800" />
            </div>
          </MyCard>
          <MyCard>
            <div class="flex justify-between items-center">
              <div>
                <p class="text-sm text-gray-500">Revenue</p>
                <p class="text-lg font-semibold">
                  {{ formatCurrency(revenue) }}
                </p>
              </div>
              <Coins class="w-10 h-10 text-gray-800" />
            </div>
          </MyCard>
          <MyCard>
            <div class="flex justify-between items-center">
              <div>
                <p class="text-sm text-gray-500">Avg Order Value</p>
                <p class="text-lg font-semibold">
                  {{ formatCurrency(avgOrderValue) }}
                </p>
              </div>
              <BarChart2 class="w-10 h-10 text-gray-800" />
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
              v-model="type"
              :options="types"
              placeholder="All Type"
              class="flex-1 min-w-[150px]"
              :show-clear="false"
            />
            <MyDropdown
              v-model="status"
              :options="statuses"
              placeholder="All Status"
              class="flex-1 min-w-[150px]"
              :show-clear="false"
            />
          </div>
        </MyCard>

        <!-- Customers Table -->
        <div class="overflow-x-auto">
          <MyTable
            title="Customers"
            :count="filteredCustomers.length"
            :items="filteredCustomers"
            :page-size="5"
          >
            <template #icon>
              <User class="w-5 h-5 text-gray-900" />
            </template>
            <template #header>
              <th class="px-6 py-3 font-medium">Customer</th>
              <th class="px-6 py-3 font-medium">Contact</th>
              <th class="px-6 py-3 font-medium">Type</th>
              <th class="px-6 py-3 font-medium">Orders</th>
              <th class="px-6 py-3 font-medium">Total Spent</th>
              <th class="px-6 py-3 font-medium">Credit Limit</th>
              <th class="px-6 py-3 font-medium">Status</th>
              <th class="px-6 py-3 font-medium">Actions</th>
            </template>

            <template #body="{ items }">
              <tr v-for="(customer, i) in items" :key="i">
                <td class="px-6 py-4">
                  <div class="flex flex-col">
                    <span class="font-medium">{{ customer.customer }}</span>
                    <span class="text-sm text-gray-500">{{ customer.code }}</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex flex-col">
                    <span class="font-medium">{{ customer.email }}</span>
                    <span class="text-sm text-gray-500">{{ customer.telp }}</span>
                  </div>
                </td>
                <td class="px-6 py-4">{{ customer.type }}</td>
                <td class="px-6 py-4">{{ customer.orders }} items</td>
                <td class="px-6 py-4">{{ formatCurrency(customer.total_spent) }}</td>
                <td class="px-6 py-4">{{ formatCurrency(customer.credit_limit) }}</td>
                <td class="px-6 py-4">
                  <StatusBadge :status="customer.status">
                    {{ customer.status }}
                  </StatusBadge>
                </td>
                <td class="px-6 py-4 flex gap-2 flex-wrap">
                  <button
                    class="text-blue-600 hover:text-blue-800"
                    @click="openEdit(customer)"
                  >
                    <Pencil class="w-4 h-4" />
                  </button>
                  <button
                    class="text-red-600 hover:text-red-800"
                    @click="openDelete(customer)"
                  >
                    <Trash class="w-4 h-4" />
                  </button>
                </td>
              </tr>

              <tr v-if="items.length === 0">
                <td colspan="8" class="text-center py-4 text-gray-500">
                  No customers found.
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
          ? 'Add Customer'
          : slideoutMode === 'edit'
          ? 'Edit Customer'
          : 'Delete Customer'
      "
      @close="showSlideout = false"
    >
      <!-- Form Add / Edit -->
      <template v-if="slideoutMode === 'add' || slideoutMode === 'edit'">
        <form class="flex flex-col gap-4">
          <MyInputField
            label="Customer Code"
            v-model="form.code"
            placeholder="CUST-XXX"
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
          <MyInputField
            label="Phone"
            v-model="form.telp"
            placeholder="Phone Number"
          />
          <MyDropdown
            label="Type"
            v-model="form.type"
            :options="types.slice(1)"
            placeholder="Select Type"
          />
          <MyInputField
            label="Orders"
            v-model="form.orders"
            type="number"
            placeholder="Total Orders"
          />
          <MyInputField
            label="Total Spent"
            v-model="form.total_spent"
            type="number"
            placeholder="Total Spent"
          />
          <MyInputField
            label="Credit Limit"
            v-model="form.credit_limit"
            type="number"
            placeholder="Credit Limit"
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
          <span class="font-semibold">{{ currentCustomer?.code }}</span
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
            @click="saveCustomer"
          >
            Submit
          </MyButton>

          <MyButton
            v-else-if="slideoutMode === 'edit'"
            color="primary"
            variant="filled"
            size="md"
            class="w-full md:w-auto"
            @click="updateCustomer"
          >
            Update
          </MyButton>

          <MyButton
            v-else-if="slideoutMode === 'delete'"
            color="error"
            variant="filled"
            size="md"
            class="w-full md:w-auto"
            @click="deleteCustomer"
          >
            Delete
          </MyButton>
        </div>
      </template>
    </Slideout>
  </div>
</template>
