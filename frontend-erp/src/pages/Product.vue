<script setup>
import { ref, computed, onMounted, watch } from "vue";
import axios from "../axios.js";
import Sidebar from "../components/Sidebar.vue";
import PageHeader from "../components/PageHeader.vue";
import MyCard from "../components/MyCard.vue";
import MyInputField from "../components/MyInputField.vue";
import MyDropdown from "../components/MyDropdown.vue";
import MyTable from "../components/MyTable.vue";
import StatusBadge from "../components/StatusBadge.vue";
import Slideout from "../components/Slideout.vue";
import MyButton from "../components/MyButton.vue";
import { Plus, Pencil, Trash, Filter, Package } from "lucide-vue-next";

// =====================
// STATES
// =====================
const search = ref("");
const category = ref("");
const status = ref("");
const stockThreshold = ref(30);

const statuses = [
  { label: "All Status", value: "" },
  { label: "In Stock", value: "in" },
  { label: "Out of Stock", value: "out" },
  { label: "Low Stock", value: "low" },
];

// =====================
// SUPPLIERS
// =====================
const suppliers = ref([]);

async function fetchSuppliers() {
  const res = await axios.get("/suppliers");
  suppliers.value = res.data;
}

// Filtered supplier untuk dropdown di Slideout
const filteredSuppliers = computed(() => {
  if (!form.value.category) return suppliers.value;
  return suppliers.value.filter(
    (s) => s.category.toLowerCase() === form.value.category.toLowerCase()
  );
});

// =====================
// CATEGORY UNTUK SLIDEOUT (AMBIL DARI SUPPLIER)
// =====================
const filteredCategoriesSlideout = computed(() => {
  const uniqueCategories = [...new Set(suppliers.value.map((s) => s.category))];

  if (!form.value.supplier_id) {
    return uniqueCategories.map((c) => ({ label: c, value: c }));
  }

  const selectedSupplier = suppliers.value.find(
    (s) => s.supplier_id === form.value.supplier_id
  );

  return selectedSupplier
    ? [{ label: selectedSupplier.category, value: selectedSupplier.category }]
    : uniqueCategories.map((c) => ({ label: c, value: c }));
});

// =====================
// CATEGORY UNTUK FILTER (AMBIL DARI PRODUCTS)
// =====================
const filterCategories = computed(() => {
  const uniqueCategories = [...new Set(products.value.map((p) => p.category))];
  return [
    { label: "All Categories", value: "" },
    ...uniqueCategories.map((c) => ({
      label: c,
      value: c,
    })),
  ];
});

// =====================
// PRODUCTS
// =====================
const products = ref([]);
const loading = ref(false);
const rawProducts = ref([]);

// Slideout
const showSlideout = ref(false);
const slideoutMode = ref("add");
const currentProduct = ref(null);

// Form
const form = ref({
  name: "",
  supplier_id: "",
  sku: "",
  category: "",
  stock: 0,
  cost: "",
  price: "",
  margin: "",
});

// Fetch products dari API
async function fetchProducts() {
  loading.value = true;
  try {
    const res = await axios.get("/products");
    console.log("API Response:", res.data);

    rawProducts.value = res.data;

    products.value = res.data.map((p) => ({
      ...p,
      supplier: p.supplier ? p.supplier.name : "No Supplier",
      formattedCost: "Rp" + Number(p.cost).toLocaleString(),
      formattedPrice: "Rp" + Number(p.price).toLocaleString(),
      formattedMargin: Number(p.margin ?? 0).toFixed(1) + "%",
    }));
  } finally {
    loading.value = false;
  }
}

// Hitung status stok
function getStatus(product) {
  const stockNum = Number(product.stock.toString().replace(/\D/g, ""));
  if (stockNum === 0) return { value: "out", label: "Out of Stock" };
  else if (stockNum <= stockThreshold.value)
    return { value: "low", label: "Low Stock" };
  else return { value: "in", label: "In Stock" };
}

// Filter products
const filteredProducts = computed(() => {
  return products.value
    .map((p) => {
      const { value, label } = getStatus(p);
      return { ...p, status: value, statusLabel: label };
    })
    .filter((p) => {
      const matchesSearch =
        search.value === "" ||
        p.name.toLowerCase().includes(search.value.toLowerCase()) ||
        p.sku.toLowerCase().includes(search.value.toLowerCase());

      const matchesCategory =
        category.value === "" ||
        p.category.toLowerCase() === category.value.toLowerCase();

      const matchesStatus = status.value === "" || p.status === status.value;

      return matchesSearch && matchesCategory && matchesStatus;
    });
});

// =====================
// SLIDEOUT HANDLERS
// =====================
function openAdd() {
  slideoutMode.value = "add";
  form.value = {
    name: "",
    supplier_id: "",
    sku: "",
    category: "",
    stock: 0,
    cost: "",
    price: "",
    margin: "",
  };
  showSlideout.value = true;
}

function openEdit(product) {
  slideoutMode.value = "edit";
  form.value = { ...product };
  currentProduct.value = product;
  showSlideout.value = true;
}

function openDelete(product) {
  slideoutMode.value = "delete";
  currentProduct.value = product;
  showSlideout.value = true;
}

function closeSlideout() {
  showSlideout.value = false;
}

// =====================
// CRUD
// =====================
async function saveProduct() {
  try {
    const res = await axios.post("/products", {
      name: form.value.name,
      supplier_id: form.value.supplier_id,
      sku: form.value.sku,
      category: form.value.category,
      stock: Number(form.value.stock),
      cost: Number(form.value.cost.toString().replace(/\D/g, "")),
      price: Number(form.value.price.toString().replace(/\D/g, "")),
    });
    const newProduct = {
      ...res.data,
      supplier: suppliers.value.find(
        (s) => s.supplier_id === form.value.supplier_id
      )?.name,
      formattedCost: "Rp" + Number(res.data.cost).toLocaleString(),
      formattedPrice: "Rp" + Number(res.data.price).toLocaleString(),
      formattedMargin: Number(res.data.margin ?? 0).toFixed(1) + "%",
    };
    products.value.push(newProduct);
    showSlideout.value = false;
  } catch (err) {
    console.error(err);
  }
}

async function updateProduct() {
  try {
    const res = await axios.put(
      `/products/${currentProduct.value.product_id}`,
      {
        name: form.value.name,
        supplier_id: form.value.supplier_id,
        sku: form.value.sku,
        category: form.value.category,
        stock: Number(form.value.stock),
        cost: Number(form.value.cost.toString().replace(/\D/g, "")),
        price: Number(form.value.price.toString().replace(/\D/g, "")),
      }
    );

    const index = products.value.findIndex(
      (p) => p.product_id === currentProduct.value.product_id
    );

    if (index !== -1) {
      const updatedProduct = {
        ...res.data,
        supplier: suppliers.value.find(
          (s) => s.supplier_id === form.value.supplier_id
        )?.name,
        formattedCost: "Rp" + Number(res.data.cost).toLocaleString(),
        formattedPrice: "Rp" + Number(res.data.price).toLocaleString(),
        formattedMargin: Number(res.data.margin ?? 0).toFixed(1) + "%",
      };
      products.value[index] = updatedProduct; // <-- ganti data lama
    }

    showSlideout.value = false;
  } catch (err) {
    console.error(err);
  }
}


async function deleteProduct() {
  try {
    await axios.delete(`/products/${currentProduct.value.product_id}`);
    products.value = products.value.filter(
      (p) => p.product_id !== currentProduct.value.product_id
    );
    showSlideout.value = false;
  } catch (err) {
    console.error(err);
  }
}

// =====================
// WATCHERS
// =====================
watch(
  () => form.value.supplier_id,
  (newVal) => {
    if (newVal) {
      const selectedSupplier = suppliers.value.find(
        (s) => s.supplier_id === newVal
      );
      if (selectedSupplier) {
        form.value.category = selectedSupplier.category;
      }
    }
  }
);

watch(
  () => form.value.category,
  (newVal) => {
    if (!newVal) {
      form.value.supplier_id = "";
    }
  }
);

// =====================
// MOUNT
// =====================
onMounted(() => {
  fetchSuppliers();
  fetchProducts();
});
</script>

<template>
  <div class="relative">
    <div class="flex flex-col lg:flex-row min-h-screen bg-white">
      <Sidebar class="w-full lg:w-1/6" />
      <div class="flex-1 flex flex-col p-4 md:px-4 md:py-6 gap-6">
        <PageHeader
          title="Product Management"
          subtitle="Welcome to your wholesale management system"
          buttonText="Add Product"
          buttonIcon="plus"
          @button-click="openAdd"
        />

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
              v-model="category"
              :options="filterCategories"
              placeholder="All Categories"
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

        <!-- TABLE -->
        <div class="overflow-x-auto">
          <MyTable
            title="Products"
            :count="filteredProducts.length"
            :items="filteredProducts"
            :page-size="5"
          >
            <template #icon>
              <Package class="w-5 h-5 text-gray-900" />
            </template>
            <template #header>
              <th class="px-6 py-3 font-medium">Product</th>
              <th class="px-6 py-3 font-medium">SKU</th>
              <th class="px-6 py-3 font-medium">Category</th>
              <th class="px-6 py-3 font-medium">Stock</th>
              <th class="px-6 py-3 font-medium">Cost</th>
              <th class="px-6 py-3 font-medium">Price</th>
              <th class="px-6 py-3 font-medium">Margin</th>
              <th class="px-6 py-3 font-medium">Status</th>
              <th class="px-6 py-3 font-medium">Actions</th>
            </template>
            <template #body="{ items }">
              <tr v-for="(product, i) in items" :key="i">
                <td class="px-6 py-4">
                  <div class="flex flex-col">
                    <span class="font-medium">{{ product.name }}</span>
                    <span class="text-sm text-gray-500">
                      {{ product.supplier }}
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4">{{ product.sku }}</td>
                <td class="px-6 py-4">{{ product.category }}</td>
                <td class="px-6 py-4">{{ product.stock }}</td>
                <td class="px-6 py-4">{{ product.formattedCost }}</td>
                <td class="px-6 py-4">{{ product.formattedPrice }}</td>
                <td class="px-6 py-4">{{ product.formattedMargin }}</td>
                <td class="px-6 py-4">
                  <StatusBadge :status="product.status">
                    {{ product.statusLabel }}
                  </StatusBadge>
                </td>
                <td class="px-6 py-4 flex gap-2 flex-wrap">
                  <button
                    class="text-blue-600 hover:text-blue-800"
                    @click="openEdit(product)"
                  >
                    <Pencil class="w-4 h-4" />
                  </button>
                  <button
                    class="text-red-600 hover:text-red-800"
                    @click="openDelete(product)"
                  >
                    <Trash class="w-4 h-4" />
                  </button>
                </td>
              </tr>
              <tr v-if="items.length === 0">
                <td colspan="9" class="text-center py-4 text-gray-500">
                  No products found.
                </td>
              </tr>
            </template>
          </MyTable>
        </div>
      </div>
    </div>

    <!-- SLIDEOUT -->
    <Slideout
      :open="showSlideout"
      :title="
        slideoutMode === 'add'
          ? 'Add Product'
          : slideoutMode === 'edit'
          ? 'Edit Product'
          : 'Delete Product'
      "
      @close="showSlideout = false"
    >
      <!-- Form untuk add/edit -->
      <template v-if="slideoutMode === 'add' || slideoutMode === 'edit'">
        <form class="flex flex-col gap-4">
          <MyInputField
            label="Product Name"
            v-model="form.name"
            placeholder="Product Name"
          />

          <MyDropdown
            label="Supplier"
            v-model="form.supplier_id"
            :options="
              filteredSuppliers.map((s) => ({
                label: s.name,
                value: s.supplier_id,
              }))
            "
            placeholder="Select Supplier"
          />

          <MyInputField label="SKU" v-model="form.sku" placeholder="SKU" />

          <!-- Category di Slideout ambil dari SUPPLIER -->
          <MyDropdown
            label="Category"
            v-model="form.category"
            :options="filteredCategoriesSlideout"
            placeholder="Select Category"
          />

          <MyInputField
            label="Stock"
            v-model="form.stock"
            type="number"
            placeholder="Stock"
          />
          <MyInputField label="Cost" v-model="form.cost" placeholder="Cost" />
          <MyInputField
            label="Price"
            v-model="form.price"
            placeholder="Price"
          />
        </form>
      </template>

      <!-- Delete confirmation -->
      <template v-else-if="slideoutMode === 'delete'">
        <p class="text-gray-700">
          Are you sure you want to delete
          <span class="font-semibold">{{ currentProduct?.name }}</span
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
            @click="saveProduct"
          >
            Submit
          </MyButton>

          <MyButton
            v-else-if="slideoutMode === 'edit'"
            color="primary"
            variant="filled"
            size="md"
            class="w-full md:w-auto"
            @click="updateProduct"
          >
            Update
          </MyButton>

          <MyButton
            v-else-if="slideoutMode === 'delete'"
            color="error"
            variant="filled"
            size="md"
            @click="deleteProduct"
          >
            Delete
          </MyButton>
        </div>
      </template>
    </Slideout>
  </div>
</template>
