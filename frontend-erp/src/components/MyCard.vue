<template>
  <div
    :class="[
      'bg-white rounded-xl border border-gray-200 shadow-sm',
      padding,
      customClass
    ]"
  >
    <!-- Jika ada title -->
    <div v-if="title || subtitle || $slots.icon" class="mb-3 flex items-center gap-2">
      <!-- slot untuk icon -->
      <slot name="icon" />

      <div>
        <h3 v-if="title" class="text-sm font-medium text-gray-900">
          {{ title }}
        </h3>
        <p v-if="subtitle" class="text-xs text-gray-500">
          {{ subtitle }}
        </p>
      </div>
    </div>

    <!-- Isi konten card -->
    <slot />

    <!-- Footer opsional -->
    <div v-if="$slots.footer" class="mt-3 border-t border-gray-100 pt-3">
      <slot name="footer" />
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  title: { type: String, default: "" },
  subtitle: { type: String, default: "" },
  padding: { type: String, default: "p-6" }, // bisa "p-4", "p-6", dll.
  customClass: { type: String, default: "" },
});
</script>
