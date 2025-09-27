<template>
  <span
    :class="[
      'px-3 py-1 text-xs font-medium rounded-full',
      status === 'in' && 'bg-blue-50 text-blue-600',
      status === 'low' && 'bg-yellow-50 text-yellow-600',
      status === 'out' && 'bg-red-50 text-red-600',
    ]"
  >
    <slot />
  </span>
</template>

<script setup>
defineProps({
  status: {
    type: String,
    required: true,
  },
});
</script>
