<template>
  <div
    class="flex justify-between items-center py-2 px-3 text-gray-400 bg-white shadow-sm border-b font-semibold">
    <div v-if="!expand || (expand && !screen)" class="flex flex-row items-center space-x-2">
      <i class="fp-eye" />

      <span>{{ $t("Preview") }}</span>
    </div>

    <div v-if="expand && screen" class="flex flex-row items-center space-x-2">
      <span class="isolate inline-flex rounded-md shadow-sm">
        <button
          v-for="(button, index) of buttons"
          :key="index"
          @click="updateButton(button, index)"
          type="button"
          class="relative inline-flex items-center px-3 py-2 ring-1 ring-inset ring-gray-300 hover:bg-gray-300 focus:z-10"
          :class="{
            'bg-gray-400 text-white':screenSize === button.size,
            'rounded-l-md': index == 0, 'rounded-r-md': index== buttons.length-1}">
          <i :class="button.component" />
        </button>
      </span>
    </div>

    <div class="flex items-center">
      <div class="flex items-center space-x-8">
        <button
          v-if="!expand"
          @click="onExpand"
          type="button"
          class="btn border flex items-center space-x-3 rounded-md shadow-sm text-gray-500 capitalize">
          <i class="fp-fullscreen h-5 w-5" />

          <span>{{ $t("Full Screen") }}</span>
        </button>

        <span @click="onClose" class="py-2 hover:opacity-75">
          <i class="fp-close text-sm" />
        </span>
      </div>
    </div>
  </div>
</template>

<script>
import {
  defineComponent, ref, computed,
} from 'vue';
import Vue from 'Vue';

export default defineComponent({
  emits: ['changeSize'],
  props: ['useSlideShow'],
  setup(props, { emit }) {
    const data = ref();
    const image = ref();
    const previewData = ref();
    const { panelConfig, screenSize, screen } = props.useSlideShow;
    const buttons = ref([
      { component: 'fp-desktop', size: 'desktop' },
      { component: 'fp-mobile', size: 'mobile' },
    ]);

    const expand = computed(() => panelConfig.value == 'right-panel');

    const updateButton = (button) => {
      Vue.nextTick(() => {
        emit('changeSize', button.size);
      });
    };

    const onExpand = () => panelConfig.value = 'right-panel';

    const onClose = () => {
      // If fullscren mode is enabled, set the two-panel mode
      if (panelConfig.value === 'right-panel') {
        panelConfig.value = 'two-panels';

        updateButton(buttons.value[0]);

        return;
      }

      // Else set the left-panel mode
      panelConfig.value = 'left-panel';
    };

    return {
      onClose,
      onExpand,
      expand,
      data,
      buttons,
      updateButton,
      image,
      previewData,
      panelConfig,
      screenSize,
      screen,
    };
  },
});
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
