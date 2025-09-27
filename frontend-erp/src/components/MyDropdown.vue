<template>
  <div class="relative w-full flex flex-col gap-1">
    <!-- Label -->
    <label v-if="label" class="text-sm font-medium text-gray-600">
      {{ label }}
    </label>

    <!-- Box atas -->
    <div
      class="px-4 py-2 border border-gray-300 rounded-lg bg-white
             text-gray-700 text-sm cursor-pointer select-none
             flex items-center justify-between
             hover:border-gray-400 transition-all"
      @click="toggleDropdown"
    >
      <span>{{ selectedLabel || placeholder }}</span>
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="w-4 h-4 text-gray-400 transition-transform"
        :class="{ 'rotate-180': showDropdown }"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </div>

    <!-- List dropdown -->
    <transition name="fade">
      <div
        v-if="showDropdown"
        class="absolute left-0 top-full mt-1 w-full z-50 bg-white border border-gray-200
               rounded-lg shadow-md overflow-hidden"
      >
        <div
          v-for="(opt, i) in options"
          :key="i"
          @click="selectOption(opt)"
          class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer"
        >
          {{ opt.label }}
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";

const props = defineProps({
  modelValue: [String, Number],
  options: { type: Array, default: () => [] },
  placeholder: { type: String, default: "Select option" },
  label: { type: String, default: "" }, // 🔹 Tambah props label
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

// Klik di luar untuk nutup dropdown
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
