<template>
  <div ref="appContainer" class="flex flex-col h-100 max-h-full bg-gray-100 ">
    <HeaderSlideshow :use-slide-show="useSlideShow" ref="headerContainer" class="shrink box-border" />

    <PanelResizable
      v-model="panelConfig"
      class="grow box-border"
      :style="{
        'height':`${height}px`,
      }">
      <template #main-panel>
        <WithoutPermissions class="mx-4 my-3 pb-32" />
      </template>

      <template #left-panel>
        <ModelerSection
          ref="modelerContainer"
          :use-slide-show="useSlideShow"
          :use-navigation="useNavigation" />
      </template>

      <template #right-panel>
        <ScreenSection
          @expand="showScreenPanel"
          @close="showModelerPanel"
          @twoPanel="showTwoPanels"
          @prev="onPrev"
          @next="onNext"
          :use-navigation="useNavigation"
          :use-slide-show="useSlideShow" />
      </template>
    </PanelResizable>
  </div>
</template>

<script>
import {
  defineComponent, ref, onMounted,
} from 'vue';
import ScreenSection from './components/screenPanel/ScreenSection.vue';
import ModelerSection from './components/ModelerSection.vue';
import PanelResizable from './components/PanelResizable.vue';
import HeaderSlideshow from './components/HeaderSlideshow.vue';
import WithoutPermissions from './components/screens/WithoutPermissions.vue';
import useComposable from './useComposable';
import { useNavigation as useNavComposable } from './useNavigation';

export default defineComponent({
  components: {
    ScreenSection,
    ModelerSection,
    HeaderSlideshow,
    PanelResizable,
    WithoutPermissions,
  },
  setup() {
    const appContainer = ref();
    const screenContainer = ref();
    const headerContainer = ref();
    const modelerContainer = ref();
    const height = ref();
    const useSlideShow = useComposable({ appContainer, screenContainer, modelerContainer });
    const useNavigation = useNavComposable();

    const { panelConfig } = useSlideShow;

    const showTwoPanels = () => {
      panelConfig.value = 'two-panels';
    };

    const showModelerPanel = () => {
      panelConfig.value = 'left-panel';
    };

    const showScreenPanel = () => {
      panelConfig.value = 'right-panel';
    };

    const onNext = () => {
      if (modelerContainer.value.onNext) {
        modelerContainer.value.onNext();
      }
    };

    const onPrev = () => {
      if (modelerContainer.value.onPrev) {
        modelerContainer.value.onPrev();
      }
    };

    onMounted(() => {
      const hApp = appContainer.value.getBoundingClientRect().height;
      const hHeader = headerContainer.value.$el.getBoundingClientRect().height;

      height.value = hApp - hHeader;
    });

    return {
      showModelerPanel,
      showScreenPanel,
      showTwoPanels,
      panelConfig,
      useSlideShow,
      useNavigation,
      onPrev,
      onNext,
      appContainer,
      screenContainer,
      modelerContainer,
      headerContainer,
      height,
    };
  },
});
</script>
<style>
#app-package-show{
  height: 100%;
  max-height: 100vh;
}
</style>
