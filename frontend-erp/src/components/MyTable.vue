<template>
  <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm">
    <!-- Top Header -->
    <div class="flex items-center justify-between px-4 py-3">
      <div class="flex items-center gap-2">
        <!-- slot icon -->
        <slot name="icon"></slot>
        <h2 class="text-sm text-gray-600">
          {{ title }}
          <span v-if="count !== null" class="text-sm text-gray-600">({{ count }})</span>
        </h2>
      </div>

      <!-- slot untuk aksi tambahan (misalnya tombol Add) -->
      <div>
        <slot name="actions"></slot>
      </div>
    </div>

    <!-- Table -->
    <table class="min-w-full text-sm text-left divide-y divide-gray-200">
      <!-- Header -->
      <thead class="bg-gray-50 text-gray-600">
        <tr>
          <slot name="header"></slot>
        </tr>
      </thead>

      <!-- Body -->
      <tbody class="divide-y divide-gray-200">
        <!-- render data sesuai pagination -->
        <slot name="body" :items="paginatedItems"></slot>
      </tbody>
    </table>

    <!-- Pagination -->
    <div
      v-if="showPagination && totalPages > 1"
      class="flex items-center justify-between px-4 py-3 border-t border-gray-200 bg-gray-50"
    >
      <p class="text-sm text-gray-600">
        Page {{ currentPage }} of {{ totalPages }}
      </p>
      <div class="flex gap-2">
        <button
          @click="prevPage"
          :disabled="currentPage === 1"
          class="px-3 py-1 border border-gray-300 rounded-lg disabled:opacity-50"
        >
          Previous
        </button>
        <button
          @click="nextPage"
          :disabled="currentPage === totalPages"
          class="px-3 py-1 border border-gray-300 rounded-lg disabled:opacity-50"
        >
          Next
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, watch } from "vue";

const props = defineProps({
  title: { type: String, default: "" },
  count: { type: Number, default: null },
  items: { type: Array, default: () => [] }, // semua data produk
  pageSize: { type: Number, default: 6 }, // default tampil 6 per halaman
  showPagination: { type: Boolean, default: true },
});

const emit = defineEmits(["pageChange"]);

const currentPage = ref(1);

const totalPages = computed(() => {
  return Math.ceil(props.items.length / props.pageSize) || 1;
});

const paginatedItems = computed(() => {
  const start = (currentPage.value - 1) * props.pageSize;
  return props.items.slice(start, start + props.pageSize);
});

function nextPage() {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
    emit("pageChange", currentPage.value);
  }
}

function prevPage() {
  if (currentPage.value > 1) {
    currentPage.value--;
    emit("pageChange", currentPage.value);
  }
}

// reset ke page 1 kalau items berubah
watch(
  () => props.items,
  () => {
    currentPage.value = 1;
  }
);
</script>
