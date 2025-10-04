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
  UserCheck,
  Coins,
  Star,
  Pencil,
  Trash,
  Plus,
} from "lucide-vue-next";

// =============================
// State Filters
// =============================
const search = ref("");
const status = ref("");
const statuses = [
  { label: "All Status", value: "" },
  { label: "Active", value: "active" },
  { label: "Inactive", value: "inactive" },
];

// =============================
// Dummy Suppliers
// =============================
const suppliers = ref([
  {
    id: 1,
    code: "ABC-001",
    supplier: "ABC Mart",
    email: "suppliers@abcmart.com",
    telp: "(021) 555-1234",
    category: "General Goods",
    rating: 4.8,
    orders: 25,
    totalPurchased: 38750,
    terms: {
      payment: "Net 30 / COD",
      delivery: "3-5 days",
    },
    status: "active",
  },
  {
    id: 2,
    code: "QSS-002",
    supplier: "QuickStop Store",
    email: "purchasing@quickstop.com",
    telp: "(021) 555-5678",
    category: "Groceries",
    rating: 4.5,
    orders: 120,
    totalPurchased: 58125,
    terms: {
      payment: "Due on Receipt",
      delivery: "1-2 days",
    },
    status: "active",
  },
  {
    id: 3,
    code: "RH-003",
    supplier: "Retail Hub",
    email: "suppliers@retailhub.com",
    telp: "(021) 555-9012",
    category: "Retail",
    rating: 3.9,
    orders: 15,
    totalPurchased: 150000,
    terms: {
      payment: "Net 60",
      delivery: "5-7 days",
    },
    status: "inactive",
  },
  {
    id: 4,
    code: "GS-004",
    supplier: "Global Supplies",
    email: "suppliers@globalsupplies.com",
    telp: "(021) 555-3456",
    category: "Industrial",
    rating: 4.9,
    orders: 50,
    totalPurchased: 250000,
    terms: {
      payment: "Net 30",
      delivery: "7-10 days",
    },
    status: "active",
  },
  {
    id: 5,
    code: "WD-005",
    supplier: "Wholesale Direct",
    email: "suppliers@wholesaledirect.com",
    telp: "(021) 555-7890",
    category: "Wholesale",
    rating: 4.7,
    orders: 85,
    totalPurchased: 500000,
    terms: {
      payment: "Net 60 / COD",
      delivery: "5-8 days",
    },
    status: "active",
  },
]);

// =============================
// Computed: Filter Suppliers
// =============================
const filteredSuppliers = computed(() => {
  return suppliers.value.filter((supplier) => {
    const matchesSearch =
      search.value === "" ||
      supplier.supplier.toLowerCase().includes(search.value.toLowerCase()) ||
      supplier.code.toLowerCase().includes(search.value.toLowerCase());

    const matchesStatus =
      status.value === "" || supplier.status === status.value;

    return matchesSearch && matchesStatus;
  });
});

// =============================
// Slideout Logic
// =============================
const showSlideout = ref(false);
const slideoutMode = ref("add");
const currentSupplier = ref(null);

const form = ref({
  code: "",
  supplier: "",
  email: "",
  telp: "",
  category: "",
  rating: 0,
  orders: 0,
  totalPurchased: 0,
  terms: { payment: "", delivery: "" },
  status: "active",
});

// Open Add/Edit/Delete
function openAdd() {
  slideoutMode.value = "add";
  form.value = {
    code: "",
    supplier: "",
    email: "",
    telp: "",
    category: "",
    rating: 0,
    orders: 0,
    totalPurchased: 0,
    terms: { payment: "", delivery: "" },
    status: "active",
  };
  showSlideout.value = true;
}

function openEdit(supplier) {
  slideoutMode.value = "edit";
  form.value = JSON.parse(JSON.stringify(supplier));
  currentSupplier.value = supplier;
  showSlideout.value = true;
}

function openDelete(supplier) {
  slideoutMode.value = "delete";
  currentSupplier.value = supplier;
  showSlideout.value = true;
}

function closeSlideout() {
  showSlideout.value = false;
}

// =============================
// CRUD Dummy
// =============================
function saveSupplier() {
  const newSupplier = {
    ...form.value,
    id: Date.now(),
  };
  suppliers.value.push(newSupplier);
  showSlideout.value = false;
}

function updateSupplier() {
  const index = suppliers.value.findIndex(
    (s) => s.id === currentSupplier.value.id
  );
  if (index !== -1) suppliers.value[index] = JSON.parse(JSON.stringify(form.value));
  showSlideout.value = false;
}

function deleteSupplier() {
  suppliers.value = suppliers.value.filter(
    (s) => s.id !== currentSupplier.value.id
  );
  showSlideout.value = false;
}

// =============================
// Helpers for Cards
// =============================
const totalSuppliers = computed(() => suppliers.value.length);
const activeSuppliers = computed(() =>
  suppliers.value.filter((s) => s.status === "active").length
);
const totalOrders = computed(() =>
  suppliers.value.reduce((sum, s) => sum + s.orders, 0)
);
const avgRating = computed(() => {
  const total = suppliers.value.reduce((sum, s) => sum + s.rating, 0);
  return (total / suppliers.value.length).toFixed(1);
});

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
          title="Supplier Management"
          subtitle="Manage your wholesale suppliers"
          buttonText="Add Supplier"
          buttonIcon="plus"
          @button-click="openAdd"
        />

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <MyCard>
            <div class="flex justify-between items-center">
              <div>
                <p class="text-sm text-gray-500">Total Suppliers</p>
                <p class="text-lg font-semibold">{{ totalSuppliers }}</p>
              </div>
              <ShoppingCart class="w-10 h-10 text-gray-800" />
            </div>
          </MyCard>

          <MyCard>
            <div class="flex justify-between items-center">
              <div>
                <p class="text-sm text-gray-500">Active Suppliers</p>
                <p class="text-lg font-semibold">{{ activeSuppliers }}</p>
              </div>
              <UserCheck class="w-10 h-10 text-gray-800" />
            </div>
          </MyCard>

          <MyCard>
            <div class="flex justify-between items-center">
              <div>
                <p class="text-sm text-gray-500">Total Orders</p>
                <p class="text-lg font-semibold">{{ totalOrders }}</p>
              </div>
              <Coins class="w-10 h-10 text-gray-800" />
            </div>
          </MyCard>

          <MyCard>
            <div class="flex justify-between items-center">
              <div>
                <p class="text-sm text-gray-500">Average Rating</p>
                <p class="text-lg font-semibold">{{ avgRating }}</p>
              </div>
              <Star class="w-10 h-10 text-gray-800" />
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
              placeholder="Search Supplier / Code"
              class="flex-3 min-w-[200px]"
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

        <!-- Suppliers Table -->
        <div class="overflow-x-auto">
          <MyTable
            title="Suppliers"
            :count="filteredSuppliers.length"
            :items="filteredSuppliers"
            :page-size="5"
          >
            <template #icon>
              <ShoppingCart class="w-5 h-5 text-gray-900" />
            </template>
            <template #header>
              <th class="px-6 py-3 font-medium">Supplier</th>
              <th class="px-6 py-3 font-medium">Contact</th>
              <th class="px-6 py-3 font-medium">Category</th>
              <th class="px-6 py-3 font-medium">Rating</th>
              <th class="px-6 py-3 font-medium">Orders</th>
              <th class="px-6 py-3 font-medium">Total Purchased</th>
              <th class="px-6 py-3 font-medium">Terms</th>
              <th class="px-6 py-3 font-medium">Status</th>
              <th class="px-6 py-3 font-medium">Actions</th>
            </template>

            <template #body="{ items }">
              <tr v-for="(supplier, i) in items" :key="i">
                <td class="px-6 py-4">
                  <div class="flex flex-col">
                    <span class="font-medium">{{ supplier.supplier }}</span>
                    <span class="text-sm text-gray-500">{{ supplier.code }}</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex flex-col">
                    <span class="font-medium">{{ supplier.email }}</span>
                    <span class="text-sm">{{ supplier.telp }}</span>
                  </div>
                </td>
                <td class="px-6 py-4">{{ supplier.category }}</td>
                <td class="px-6 py-4">{{ supplier.rating }}</td>
                <td class="px-6 py-4">{{ supplier.orders }}</td>
                <td class="px-6 py-4">
                  {{ formatCurrency(supplier.totalPurchased) }}
                </td>
                <td class="px-6 py-4">
                  <div class="flex flex-col">
                    <span class="font-medium">{{ supplier.terms.payment }}</span>
                    <span class="text-sm">{{ supplier.terms.delivery }}</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <StatusBadge :status="supplier.status">{{ supplier.status }}</StatusBadge>
                </td>
                <td class="px-6 py-4 flex gap-2 flex-wrap">
                  <button class="text-blue-600 hover:text-blue-800" @click="openEdit(supplier)">
                    <Pencil class="w-4 h-4" />
                  </button>
                  <button class="text-red-600 hover:text-red-800" @click="openDelete(supplier)">
                    <Trash class="w-4 h-4" />
                  </button>
                </td>
              </tr>

              <tr v-if="items.length === 0">
                <td colspan="9" class="text-center py-4 text-gray-500">No suppliers found.</td>
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
          ? 'Add Supplier'
          : slideoutMode === 'edit'
          ? 'Edit Supplier'
          : 'Delete Supplier'
      "
      @close="closeSlideout"
    >
      <!-- Form Add / Edit -->
      <template v-if="slideoutMode === 'add' || slideoutMode === 'edit'">
        <form class="flex flex-col gap-4">
          <MyInputField label="Supplier Code" v-model="form.code" placeholder="SUP-001" />
          <MyInputField label="Supplier Name" v-model="form.supplier" placeholder="Supplier Name" />
          <MyInputField label="Email" v-model="form.email" placeholder="Email Address" />
          <MyInputField label="Phone" v-model="form.telp" placeholder="(021) 555-XXXX" />
          <MyInputField label="Category" v-model="form.category" placeholder="Category" />
          <MyInputField label="Rating" v-model="form.rating" type="number" step="0.1" placeholder="4.5" />
          <MyInputField label="Orders" v-model="form.orders" type="number" placeholder="Total Orders" />
          <MyInputField label="Total Purchased" v-model="form.totalPurchased" type="number" placeholder="Total Amount" />
          <MyInputField label="Payment Terms" v-model="form.terms.payment" placeholder="e.g. Net 30 / COD" />
          <MyInputField label="Delivery Terms" v-model="form.terms.delivery" placeholder="e.g. 3-5 days" />
          <MyDropdown
            label="Status"
            v-model="form.status"
            :options="[
              { label: 'Active', value: 'active' },
              { label: 'Inactive', value: 'inactive' },
            ]"
            placeholder="Select Status"
          />
        </form>
      </template>

      <!-- Delete Confirmation -->
      <template v-else-if="slideoutMode === 'delete'">
        <p class="text-gray-700">
          Are you sure you want to delete
          <span class="font-semibold">{{ currentSupplier?.supplier }}</span>?
        </p>
      </template>

      <!-- Footer Buttons -->
      <template #footer>
        <div class="flex md:flex-row gap-2">
          <MyButton color="secondary" variant="filled" size="md" class="w-full md:w-auto" @click="closeSlideout">
            Cancel
          </MyButton>

          <MyButton
            v-if="slideoutMode === 'add'"
            color="success"
            variant="filled"
            size="md"
            class="w-full md:w-auto"
            @click="saveSupplier"
          >
            Submit
          </MyButton>

          <MyButton
            v-else-if="slideoutMode === 'edit'"
            color="primary"
            variant="filled"
            size="md"
            class="w-full md:w-auto"
            @click="updateSupplier"
          >
            Update
          </MyButton>

          <MyButton
            v-else-if="slideoutMode === 'delete'"
            color="error"
            variant="filled"
            size="md"
            class="w-full md:w-auto"
            @click="deleteSupplier"
          >
            Delete
          </MyButton>
        </div>
      </template>
    </Slideout>
  </div>
</template>
