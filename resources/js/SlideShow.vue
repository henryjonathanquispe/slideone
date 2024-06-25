<template>
  <div ref="appContainer" class="flex flex-col h-100 max-h-full bg-gray-100 ">
    <HeaderSlideshow class="shrink box-border" />

    <PanelResizable class="grow box-border" v-model="panelConfig">
      <template #main-panel>
        <WithoutPermissions class="mx-4 my-3 pb-32" />
      </template>

      <template #left-panel>
        <ModelerSection ref="modelerContainer" @clickNode="e=> updateNode(e)" />
      </template>

      <template #right-panel>
        <ScreenSection
          @expand="showScreenPanel"
          @close="showModelerPanel"
          @twoPanel="showTwoPanels"
          :use-slide-show="useSlideShow" />
      </template>
    </PanelResizable>
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue';
import ScreenSection from './components/screenPanel/ScreenSection.vue';
import ModelerSection from './components/ModelerSection.vue';
import PanelResizable from './components/PanelResizable.vue';
import HeaderSlideshow from './components/HeaderSlideshow.vue';
import WithoutPermissions from './components/WithoutPermissions.vue';
import useComposable from './useComposable';

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
    const modelerContainer = ref();
    const useSlideShow = useComposable({ appContainer, screenContainer, modelerContainer });

    const { panelConfig } = useSlideShow;

    const { node } = useSlideShow;

    const showTwoPanels = () => {
      panelConfig.value = 'two-panels';
    };

    const showModelerPanel = () => {
      panelConfig.value = 'left-panel';
    };

    const showScreenPanel = () => {
      panelConfig.value = 'right-panel';
    };

    const updateNode = (e) => {
      node.value = e.node;
      showTwoPanels();
    };

    return {
      showModelerPanel,
      showScreenPanel,
      showTwoPanels,
      panelConfig,
      useSlideShow,
      updateNode,
      appContainer,
      screenContainer,
      modelerContainer,
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
