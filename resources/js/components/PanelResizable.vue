<template>
  <div class="flex flex-row bg-opacity-10 max-h-full h-100 w-100">
    <div v-show="mainPanel">
      <slot name="main-panel" />
    </div>
    <div
      v-show="!mainPanel"
      class="flex left-container my-3 max-h-full h-100s"
      :class="{
        expanded: leftExpanded,
        contracted: rightExpanded,
        'mx-4': !rightExpanded
      }">
      <slot name="left-panel" />
    </div>

    <div
      v-show="!mainPanel"
      class="flex flex-col right-container max-h-full h-100"
      :class="{ contracted: leftExpanded, expanded: rightExpanded }">
      <slot name="right-panel" />
    </div>
  </div>
</template>

<script>
import {
  defineComponent, ref, watch, onUnmounted,
} from 'vue';

export default defineComponent({
  props: ['value'], // "main-panel", "two-panels", "left-panel", "right-panel"
  components: { },
  setup(props) {
    const leftExpanded = ref(true);
    const rightExpanded = ref(false);
    const mainPanel = ref(false);

    const leftPanel = () => {
      leftExpanded.value = true;
      rightExpanded.value = false;
    };

    const rightPanel = () => {
      rightExpanded.value = true;
    };

    const twoPanels = () => {
      leftExpanded.value = false;
      rightExpanded.value = null;
    };

    const showMainPanel = (val) => {
      mainPanel.value = val;
    };

    const unWatch = watch(() => props.value, () => {
      if (props.value === 'main-panel') {
        showMainPanel();
        return;
      }

      if (props.value === 'left-panel') {
        leftPanel();
        return;
      }

      if (props.value === 'right-panel') {
        rightPanel();
        return;
      }

      twoPanels();
    });

    onUnmounted(() => {
      unWatch();
    });

    return {
      leftExpanded,
      rightExpanded,
      showMainPanel,
      twoPanels,
      mainPanel,
      props,
    };
  },
});
</script>

<style scoped>
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
