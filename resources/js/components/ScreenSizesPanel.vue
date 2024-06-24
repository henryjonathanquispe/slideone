<template>
  <div
    class="flex w-full h-full justify-center">
    <div :style="{width: screens[modelSize]}">
      <slot />
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, computed } from 'vue';

export default defineComponent({
  props: {
    size: {
      type: String,
      default: () => 'sm',
    },
  },
  emits: ['update:size'],
  setup(props, { emit }) {
    const modelSize = computed({
      get: () => props.size,
      set: (value) => {
        emit('update:size', value);
      },
    });

    const screens = {
      sm: '640px',
      md: '768px',
      lg: '1024px',
      xl: '1280px',
      '2xl': '1536px',
      full: '100%',
    };

    return {
      modelSize,
      screens,
    };
  },
});
</script>
