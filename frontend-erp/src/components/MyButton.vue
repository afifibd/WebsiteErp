<template>
  <button
    :value="value"
    :type="type"
    :disabled="disabled"
    :class="makeStyle"
    :id="id"
    :data-test="cypress"
    @click="$emit('click')"
  >
    <slot />
  </button>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
  type: { type: String, default: "button" },
  color: { type: String, default: "primary" }, // primary | secondary | error | success
  variant: { type: String, default: "filled" }, // filled | outlined | text
  size: { type: String, default: "md" }, // sm | md | lg | xl
  rounded: { type: String, default: "lg" },
  disabled: { type: Boolean, default: false },
  expanded: { type: Boolean, default: false },
  value: [String, Number],
  id: { type: String, default: "" },
  cypress: { type: String, default: "" },
  customClassname: { type: String, default: "" },
});

const makeStyle = computed(() => {
  let base = `
    ${props.expanded ? "w-full" : "w-max"}
    flex items-center justify-center gap-x-2 font-medium
    transition-colors duration-200
    focus:outline-none focus:ring-2 focus:ring-offset-0
    disabled:opacity-50 disabled:cursor-not-allowed
  `;

  // Colors & Variants
  if (props.color === "primary" && props.variant === "filled") {
    base += `
      text-white bg-blue-500 hover:bg-blue-600
      shadow-sm focus:ring-blue-300
    `;
  } else if (props.color === "primary" && props.variant === "outlined") {
    base += `
      text-blue-600 border border-blue-600 bg-white
      hover:bg-blue-50 focus:ring-blue-300
    `;
  } else if (props.color === "primary" && props.variant === "text") {
    base += `
      text-blue-600 hover:text-blue-700
      focus:ring-blue-200
    `;
  } else if (props.color === "secondary" && props.variant === "filled") {
    base += `
      text-white bg-gray-500 hover:bg-gray-600
      shadow-sm focus:ring-gray-300
    `;
  } else if (props.color === "error" && props.variant === "filled") {
    base += `
      text-white bg-red-600 hover:bg-red-700
      shadow-sm focus:ring-red-300
    `;
  } else if (props.color === "success" && props.variant === "filled") {
    base += `
      text-white bg-green-600 hover:bg-green-700
      shadow-sm focus:ring-green-300
    `;
  }

  // Sizes
  if (props.size === "sm") {
    base += " text-sm px-3 py-1.5";
  } else if (props.size === "md") {
    base += " text-sm px-4 py-2";
  } else if (props.size === "lg") {
    base += " text-base px-5 py-2.5";
  } else if (props.size === "xl") {
    base += " text-lg px-6 py-3";
  }

  // Rounded
  const roundedMap = {
    none: "rounded-none",
    sm: "rounded-sm",
    md: "rounded-md",
    lg: "rounded-lg",
    xl: "rounded-xl",
    full: "rounded-full",
  };
  base += ` ${roundedMap[props.rounded] ?? "rounded-lg"}`;

  // Custom class
  if (props.customClassname) {
    base += ` ${props.customClassname}`;
  }

  return base;
});
</script>
