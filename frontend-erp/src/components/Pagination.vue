<template>
  <div class="flex items-center justify-between px-4 py-3 rounded-xl border border-gray-200 shadow-sm">
    <p class="text-sm text-gray-600">Page {{ currentPage }} of {{ totalPages }}</p>
    <div class="flex gap-2">
      <button
        @click="$emit('prev')"
        :disabled="currentPage === 1"
        class="px-3 py-1 border border-gray-300 rounded-lg disabled:opacity-50"
      >
        Previous
      </button>
      <button
        @click="$emit('next')"
        :disabled="currentPage === totalPages"
        class="px-3 py-1 border border-gray-300 rounded-lg disabled:opacity-50"
      >
        Next
      </button>
    </div>
  </div>
</template>

<script setup>
defineProps({
  currentPage: Number,
  totalPages: Number,
});
</script>
