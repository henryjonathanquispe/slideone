<template>
  <div id="app">
    <div class="d-flex flex-column h-100">
      <Header @open-modal="open" />
      <div class="d-flex bg-opacity-10 h-100 w-100">
        <div
          class="d-flex left-container"
          :class="{ expanded: leftExpanded, contracted: rightExpanded }"
        >
          <ModelerSection @pickup="defaultSize" @clickNode="clickNode" />
        </div>

        <div
          class="d-flex flex-column right-container h-100"
          :class="{ contracted: leftExpanded, expanded: rightExpanded }"
        >
          <ScreenSection
            @expand="expandSidebar"
            @close="closeSidebar"
            :useSlide="useSlide"
          ></ScreenSection>
        </div>
      </div>
    </div>

    <ModalSlidePanel ref="modal" />
    <!-- Modal -->
  </div>
</template>

<script>
import ScreenSection from "./components/ScreenSection.vue";
import ModelerSection from "./components/ModelerSection.vue";
import { defineComponent, ref, onMounted } from "vue";
import ModalSlidePanel from "./components/ModalSlidePanel.vue";
import Header from "./components/Header.vue";
import { useSlideShow as useComposable } from "./composables/slideShow";

export default defineComponent({
  components: {
    ScreenSection,
    ModelerSection,
    ModalSlidePanel,
    Header,
  },
  setup() {
    const showSreenSection = ref(true);
    const modal = ref();

    const leftExpanded = ref(false);
    const rightExpanded = ref(null);

    const useSlide = useComposable();

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

    const open = () => {
      modal.value.show();
    };

    const close = () => {
      modal.value.hide();
    };

    const clickNode = (e) => {
      defaultSize();

      const { id, name } = e.node;

      useSlide.data.value = { id, name };
    };

    const goAdvancedSettings = () => {};

    return {
      showSreenSection,
      leftExpanded,
      rightExpanded,
      open,
      close,
      closeSidebar,
      modal,
      expandSidebar,
      goAdvancedSettings,
      defaultSize,
      useSlide,
      clickNode,
    };
  },
});
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
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

.space-x-1 > :not([hidden]) ~ :not([hidden]) {
  --tw-space-x-reverse: 0;
  margin-right: calc(0.25rem * var(--tw-space-x-reverse));
  margin-left: calc(0.25rem * calc(0.25 - var(--tw-space-x-reverse)));
}

.space-x-2 > :not([hidden]) ~ :not([hidden]) {
  --tw-space-x-reverse: 0;
  margin-right: calc(0.5rem * var(--tw-space-x-reverse));
  margin-left: calc(0.5rem * calc(0.5 - var(--tw-space-x-reverse)));
}

.space-x-3 > :not([hidden]) ~ :not([hidden]) {
  --tw-space-x-reverse: 0;
  margin-right: calc(0.75rem * var(--tw-space-x-reverse));
  margin-left: calc(0.75rem * calc(0.75 - var(--tw-space-x-reverse)));
}

.space-x-4 > :not([hidden]) ~ :not([hidden]) {
  --tw-space-x-reverse: 0;
  margin-right: calc(1rem * var(--tw-space-x-reverse));
  margin-left: calc(1rem * calc(1 - var(--tw-space-x-reverse)));
}
.space-x-5 > :not([hidden]) ~ :not([hidden]) {
  --tw-space-x-reverse: 0;
  margin-right: calc(1.25rem * var(--tw-space-x-reverse));
  margin-left: calc(1.25rem * calc(1.25 - var(--tw-space-x-reverse)));
}
.space-x-6 > :not([hidden]) ~ :not([hidden]) {
  --tw-space-x-reverse: 0;
  margin-right: calc(1.5rem * var(--tw-space-x-reverse));
  margin-left: calc(1.5rem * calc(1.5 - var(--tw-space-x-reverse)));
}
</style>
