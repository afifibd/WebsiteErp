<template>
  <div class="relative w-full flex flex-col gap-1">
    <!-- Label -->
    <label v-if="label" class="text-sm font-medium text-gray-600">
      {{ label }}
    </label>

    <!-- Box atas -->
    <div
      class="px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-700 text-sm cursor-pointer select-none flex items-center justify-between hover:border-gray-400 transition-all relative"
      @click="toggleDropdown"
    >
      <span class="truncate">
        {{ selectedLabel || placeholder }}
      </span>

      <div class="flex items-center gap-2">
        <!-- Tombol clear -->
        <button
          v-if="showClear && modelValue"
          @click.stop="clearSelection"
          class="text-gray-400 hover:text-red-500 transition-colors"
          title="Clear selection"
        >
          ✕
        </button>

        <!-- Icon arrow -->
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="w-4 h-4 text-gray-400 transition-transform"
          :class="{ 'rotate-180': showDropdown }"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M19 9l-7 7-7-7"
          />
        </svg>
      </div>
    </div>

    <!-- List dropdown -->
    <transition name="fade">
      <div
        v-if="showDropdown"
        class="absolute left-0 top-full mt-1 w-full z-50 bg-white border border-gray-200 rounded-lg shadow-md overflow-hidden"
      >
        <div
          v-for="(opt, i) in options"
          :key="i"
          @click="selectOption(opt)"
          class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer"
        >
          {{ opt.label }}
        </div>

        <!-- Jika tidak ada data -->
        <div
          v-if="options.length === 0"
          class="px-4 py-2 text-sm text-gray-400 text-center"
        >
          No options
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";

const props = defineProps({
  modelValue: [String, Number, null],
  options: { type: Array, default: () => [] },
  placeholder: { type: String, default: "Select option" },
  label: { type: String, default: "" },

  // 🔹 Tambahan: kontrol apakah tombol clear ditampilkan
  showClear: { type: Boolean, default: true },
});

const emit = defineEmits(["update:modelValue"]);

const showDropdown = ref(false);

const selectedLabel = computed(() => {
  const found = props.options.find((o) => o.value === props.modelValue);
  return found ? found.label : "";
});

function toggleDropdown() {
  showDropdown.value = !showDropdown.value;
}

function selectOption(option) {
  emit("update:modelValue", option.value);
  showDropdown.value = false;
}

function clearSelection() {
  emit("update:modelValue", null);
}

// Tutup jika klik di luar
function handleClickOutside(e) {
  if (!e.target.closest(".relative")) {
    showDropdown.value = false;
  }
}

onMounted(() => {
  document.addEventListener("click", handleClickOutside);
});
onBeforeUnmount(() => {
  document.removeEventListener("click", handleClickOutside);
});
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: all 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(-5px);
}
</style>
