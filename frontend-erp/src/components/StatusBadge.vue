<template>
  <span
    :class="[
      'px-3 py-1 text-xs font-medium rounded-full',
      // Product statuses
      status === 'in' && 'bg-blue-50 text-blue-600',
      status === 'low' && 'bg-yellow-50 text-yellow-600',
      status === 'out' && 'bg-red-50 text-red-600',

      // Payment statuses
      status === 'paid' && 'bg-green-50 text-green-600',
      status === 'pending' && 'bg-orange-50 text-orange-600',
      status === 'failed' && 'bg-red-50 text-red-600',

      // Order statuses
      status === 'processing' && 'bg-yellow-50 text-yellow-600',
      status === 'shipped' && 'bg-blue-50 text-blue-600',
      status === 'delivered' && 'bg-green-50 text-green-600',
      status === 'pending' && 'bg-orange-50 text-orange-600',
      status === 'canceled' && 'bg-red-50 text-red-600',

      // Customer statuses
      status === 'active' && 'bg-green-50 text-blue-600',
      status === 'inactive' && 'bg-gray-50 text-gray-600',
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
