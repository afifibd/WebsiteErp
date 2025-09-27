<template>
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-semibold text-gray-900">{{ title }}</h1>
      <p v-if="subtitle" class="text-sm text-gray-600">{{ subtitle }}</p>
    </div>

    <!-- tombol -->
    <MyButton
      v-if="showButton && buttonText"
      :color="buttonColor"
      :variant="buttonVariant"
      :size="buttonSize"
      :rounded="buttonRounded"
      @click="onButtonClick"
      class="flex items-center gap-2"
    >
      <!-- Icon selalu tampil -->
      <slot name="icon" v-if="$slots.icon" />
      <Plus v-else-if="buttonIcon === 'plus'" class="w-4 h-4" />
      <Download v-else-if="buttonIcon === 'download'" class="w-4 h-4" />

      <!-- Text hanya tampil di md ke atas -->
      <span class="hidden md:inline">{{ buttonText }}</span>
    </MyButton>
  </div>
</template>


<script setup>
import MyButton from "./MyButton.vue";
import { Plus, Download } from "lucide-vue-next";

const props = defineProps({
  title: String,
  subtitle: { type: String, default: "" },
  buttonText: { type: String, default: "" },
  showButton: { type: Boolean, default: true },
  buttonColor: { type: String, default: "primary" },
  buttonVariant: { type: String, default: "filled" },
  buttonSize: { type: String, default: "md" },
  buttonRounded: { type: String, default: "lg" },
  buttonIcon: { type: String, default: "" },
});

const emit = defineEmits(["button-click"]);

const onButtonClick = () => {
  emit("button-click");
};
</script>
