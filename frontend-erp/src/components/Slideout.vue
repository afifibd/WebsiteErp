<template>
  <transition name="slide">
    <div
      v-if="open"
      class="fixed inset-0 bg-black/40 z-40 flex justify-end"
      @click.self="$emit('close')"
    >
      <div class="w-full max-w-md bg-white shadow-xl h-full p-6 z-50 flex flex-col">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-bold">{{ title }}</h2>
          <button
            @click="$emit('close')"
            class="p-2 rounded-full hover:bg-gray-100"
          >
            ✕
          </button>
        </div>

        <!-- Body -->
        <div class="flex-1 overflow-y-auto">
          <slot></slot>
        </div>

        <!-- Footer -->
        <div v-if="$slots.footer" class="mt-4 flex justify-end gap-2">
          <slot name="footer"></slot>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
defineProps({
  open: { type: Boolean, required: true },
  title: { type: String, default: "Slideout" },
});
defineEmits(["close"]);
</script>

<style>
.slide-enter-from {
  transform: translateX(100%);
}
.slide-enter-to {
  transform: translateX(0);
}
.slide-leave-from {
  transform: translateX(0);
}
.slide-leave-to {
  transform: translateX(100%);
}
.slide-enter-active,
.slide-leave-active {
  transition: transform 0.3s ease;
}
</style>
