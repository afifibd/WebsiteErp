<template>
  <div class="flex flex-col w-full gap-1">
    <!-- Label -->
    <label v-if="label" class="text-sm font-medium text-gray-600">
      {{ label }}
    </label>

    <!-- Input -->
    <input
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :type="type"
      :placeholder="placeholder"
      :disabled="disabled"
      class="w-full px-4 py-2 text-sm rounded-lg border transition-all
             border-gray-300 bg-white text-gray-700 placeholder-gray-400
             focus:outline-none
             hover:border-gray-400
             disabled:bg-gray-100 disabled:cursor-not-allowed"
      :class="customClass"
    />

    <!-- Error -->
    <p v-if="error" class="text-sm text-red-500">
      {{ error }}
    </p>
  </div>
</template>

<script setup>
const props = defineProps({
  modelValue: { type: String, default: "" },
  type: { type: String, default: "text" },
  placeholder: { type: String, default: "" },
  customClass: { type: String, default: "" },
  label: String,
  error: String,
  disabled: Boolean,
});
</script>
