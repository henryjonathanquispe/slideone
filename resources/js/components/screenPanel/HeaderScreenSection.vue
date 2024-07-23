<template>
  <div
    class="flex justify-between items-center py-2 px-3 text-gray-500 bg-white shadow-sm border-b font-semibold">
    <div v-if="!expand || (expand && !screen)" class="flex flex-row items-center space-x-2">
      <IEye class="h-4 w-4" />

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
          <component :is="button.component" class="h-5 w-5" />
        </button>
      </span>
    </div>

    <div class="flex items-center">
      <div class="flex justify-content space-x-1">
        <button
          v-if="!expand"
          @click="onExpand"
          type="button"
          class="btn border flex items-center space-x-3 rounded-md shadow-sm text-gray-500 capitalize">
          <IFullScreen class="h-5 w-5" />

          <span>{{ $t("Full Screen") }}</span>
        </button>

        <button @click="onClose" type="button" class="btn hover:opacity-75">
          <IClose class="h-4 w-4" />
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import {
  defineComponent, ref, computed,
} from 'vue';
import { VueFormRenderer } from 'vue-form-renderer';
import Vue from 'Vue';
import NoScreenAssigned from '../screens/NoScreenAssigned.vue';
import {
  IEye, IFullScreen, IClose, IDesktop, IMobile,
} from '../../icons';
import ScreenLoading from '../ScreenLoading.vue';
import ScreenSizesPanel from '../ScreenSizesPanel.vue';

export default defineComponent({
  emits: ['changeSize'],
  components: {
    VueFormRenderer,
    NoScreenAssigned,
    IEye,
    IFullScreen,
    IClose,
    IDesktop,
    IMobile,
    ScreenLoading,
    ScreenSizesPanel,
  },
  props: ['useSlideShow'],
  setup(props, { emit }) {
    const data = ref();
    const image = ref();
    const previewData = ref();
    const { panelConfig, screenSize, screen } = props.useSlideShow;
    const buttons = ref([
      { component: 'IDesktop', size: 'desktop' },
      { component: 'IMobile', size: 'mobile' },
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
        panelConfig.value = 'two-panel';

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
