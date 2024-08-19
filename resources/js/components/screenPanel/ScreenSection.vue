<template>
  <div class="flex flex-col max-h-full grow">
    <HeaderScreenSection ref="refHeader" :use-slide-show="useSlideShow" @changeSize="changeSize" />
    <div
      class="transition-all flex flex-col overflow-y-auto overflow-x-hidden"
      :class="{'h-full': !heightScreen}"
      :style="{ 'height': `${heightScreen}px`}">
      <Transition
        name="fade"
        mode="out-in">
        <SlideScreenBuilder
          v-if="screen"
          :screen="screen"
          :screen-size="screenSize" />

        <img
          v-else-if="image && !documentation"
          class="bg-gray-50 max-h-full h-auto box-border object-contain"
          :src="image.file || image.src"
          :key="count"
          alt="">

        <ScreenPlaceholderHandler
          v-else
          :node="node"
          class="h-full bg-white"
          :use-navigation="useNavigation"
          :use-slide-show="useSlideShow" />
      </Transition>
    </div>

    <ButtonsNavigation
      ref="refNav"
      v-if="panelConfig == 'right-panel'"
      class="py-2 justify-center mt-auto"
      :show-back-button="hasPrevNode"
      :show-next-button="hasNextNode"
      @next="onNext"
      @prev="onPrev" />
  </div>
</template>

<script>
import {
  defineComponent, ref, getCurrentInstance, watch, onUnmounted, computed,
} from 'vue';
import Vue from 'Vue';
import { getScreen } from '../../utils/slideshowApi';
import HeaderScreenSection from './HeaderScreenSection.vue';
import SlideScreenBuilder from './SlideScreenBuilder.vue';
import ButtonsNavigation from '../ButtonsNavigation.vue';
import { includeNodeTypes } from '../../useComposable';
import ScreenPlaceholderHandler from './ScreenPlaceholderHandler.vue';

export default defineComponent({
  emits: ['close'],
  components: {
    HeaderScreenSection,
    SlideScreenBuilder,
    ButtonsNavigation,
    ScreenPlaceholderHandler,
  },
  props: ['useSlideShow', 'useNavigation'],
  emit: ['prev', 'next'],
  setup(props, { emit }) {
    const instance = getCurrentInstance();
    const image = ref();
    const count = ref(0);
    const previewData = ref();
    const dontSupportScreen = ref();
    const gatewayScreen = ref();
    const gatewayOptions = ref([]);
    const refNav = ref();
    const refHeader = ref();

    const {
      screenSize, panelConfig, screen, modelerContainer, node, findMapElement, alternative,
    } = props.useSlideShow;

    const {
      hasPrevNode, hasNextNode,
    } = props.useNavigation;

    const changeSize = (size) => {
      screenSize.value = size;
    };

    const getImage = () => instance.proxy.$globalStore.getters['slideshow/getImageByProps']({
      'custom_properties.node_id': node.value.id,
      'custom_properties.alternative': alternative.value,
    });

    const unWatch = watch(() => node.value, async (nVal, oVal) => {
      image.value = null;
      screen.value = null;
      dontSupportScreen.value = null;
      gatewayScreen.value = null;

      await Vue.nextTick();

      if (nVal && nVal !== oVal) {
        if (!includeNodeTypes.find((e) => nVal.$type.toLowerCase().includes(e))) {
          dontSupportScreen.value = true;

          return;
        }

        if (nVal.screenRef) {
          const res = await getScreen(nVal.screenRef);

          screen.value = res.data;

          return;
        }

        const img = getImage();

        if (img) {
          image.value = {
            src: img.original_url,
            name: img.name,
            uuid: img.uuid,
          };
        }
      }
    });

    const heightScreen = computed(() => {
      const heightModeler = modelerContainer.value?.$el?.getBoundingClientRect().height;
      const heightNavHeader = refNav.value?.$el?.getBoundingClientRect().height || 0;

      if (panelConfig.value !== 'two-panels') {
        return null;
      }

      return heightModeler - heightNavHeader;
    });

    const onPrev = () => {
      emit('prev');
    };

    const onNext = () => emit('next');

    const documentation = computed(() => {
      if (node.documentation?.length && node.documentation[0]) {
        return node.documentation[0].text;
      }

      return null;
    });

    onUnmounted(() => {
      unWatch();
    });

    return {
      image,
      screen,
      previewData,
      count,
      screenSize,
      changeSize,
      dontSupportScreen,
      gatewayScreen,
      node,
      panelConfig,
      refNav,
      refHeader,
      onPrev,
      onNext,
      hasPrevNode,
      hasNextNode,
      gatewayOptions,
      heightScreen,
      documentation,
    };
  },
});
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
