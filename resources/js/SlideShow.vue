<template>
  <div class="app-slide-show bg-gray-100">
    <div class="flex flex-col h-100">
      <Header @without-permission="showWithoutPermission" />

      <div class="flex flex-row bg-opacity-10 h-100 w-100">
        <WithoutPermissions class="mx-4 my-3 pb-32" v-show="withoutPermission" />

        <div
          v-show="!withoutPermission"
          class="flex left-container my-3"
          :class="{
            expanded: leftExpanded,
            contracted: rightExpanded,
            'mx-4': !rightExpanded
          }">
          <ModelerSection @pickup="defaultSize" @clickNode="clickNode" />
        </div>

        <div
          v-show="!withoutPermission"
          class="flex flex-col right-container h-100"
          :class="{ contracted: leftExpanded, expanded: rightExpanded }">
          <ScreenSection
            @expand="expandSidebar"
            @close="closeSidebar"
            :use-slide="useSlide" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue';
import ScreenSection from './components/ScreenSection.vue';
import ModelerSection from './components/ModelerSection.vue';
import Header from './components/Header.vue';
import { useSlideShow as useComposable } from './composables/slideShow';
import WithoutPermissions from './components/WithoutPermissions.vue';

export default defineComponent({
  components: {
    ScreenSection,
    ModelerSection,
    Header,
    WithoutPermissions,
  },
  setup() {
    const showScreenSection = ref(true);
    const leftExpanded = ref(true);
    const rightExpanded = ref(false);
    const useSlide = useComposable();
    const withoutPermission = ref(false);

    const closeSidebar = () => {
      leftExpanded.value = true;
      rightExpanded.value = false;
    };

    const expandSidebar = () => {
      rightExpanded.value = true;
    };

    const defaultSize = () => {
      leftExpanded.value = false;
      rightExpanded.value = null;
    };

    const showWithoutPermission = (val) => {
      withoutPermission.value = val;
    };

    const clickNode = (e) => {
      defaultSize();

      const { id, name } = e.node;

      useSlide.data.value = { id, name };
    };

    const goAdvancedSettings = () => {};

    return {
      showScreenSection,
      leftExpanded,
      rightExpanded,
      closeSidebar,
      showWithoutPermission,
      expandSidebar,
      goAdvancedSettings,
      defaultSize,
      useSlide,
      clickNode,
      withoutPermission,
    };
  },
});
</script>

<style>
.app-slide-show {
  height: 100vh;
  overflow: hidden;
}

.left-container,
.right-container {
  flex: 1;
  transition: all 0.3s ease;
  overflow: hidden;
}

.left-container.expanded {
  flex: 1 1 100%;
  opacity: 1;
}

.left-container.contracted {
  transform: translateX(-100%);
  opacity: 0;
}

.right-container.contracted {
  transform: translateX(100%);
  opacity: 0;
}

.right-container.expanded {
  flex: 1 1 100%;
  opacity: 1;
}
</style>
