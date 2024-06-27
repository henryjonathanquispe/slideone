<template>
  <div class="flex flex-col max-h-full grow">
    <HeaderScreenSection :use-slide-show="useSlideShow" @changeSize="changeSize" />
    <div
      class="transition-all flex flex-col justify-center content-start"
      :style="{ 'height': height + 'px' }">
      <Transition
        name="fade"
        mode="out-in">
        <SlideScreenBuilder
          v-if="screen"
          :screen="screen"
          :screen-size="screenSize" />

        <img
          v-else-if="image"
          class="bg-gray-50 max-h-full h-auto box-border object-contain"
          :src="image.file || image.src"
          :key="count"
          alt="">

        <GatewayScreen
          v-else-if="gatewayScreen"
          :title="node.name"
          :subtitle="$t('Choose a path from the gateway to continue the flow')"
          class="h-100 pb-32" />

        <DontSupportScreen
          v-else-if="dontSupportScreen"
          class="h-100 pb-32" />

        <NoScreenAssigned
          v-else
          class="h-100 pb-32" />
      </Transition>

      <ButtonsNavigation
        v-if="panelConfig == 'right-panel'"
        class="py-2 justify-center"
        @next="()=>{}"
        @prev="()=>{}" />
    </div>
  </div>
</template>

<script>
import {
  defineComponent, ref, getCurrentInstance, watch, onUnmounted, onMounted,
} from 'vue';
import Vue from 'Vue';
import NoScreenAssigned from '../screens/NoScreenAssigned.vue';
import DontSupportScreen from '../screens/DontSupportScreen.vue';
import { getScreen } from '../../utils/slideshowApi';
import HeaderScreenSection from './HeaderScreenSection.vue';
import SlideScreenBuilder from './SlideScreenBuilder.vue';
import GatewayScreen from '../screens/GatewayScreen.vue';
import ButtonsNavigation from '../ButtonsNavigation.vue';

const acceptedNodes = ['bpmn:StartEvent', 'bpmn:Task', 'bpmn:ManualTask', 'bpmn:ExclusiveGateway'];

export default defineComponent({
  emits: ['close'],
  components: {
    NoScreenAssigned,
    HeaderScreenSection,
    DontSupportScreen,
    SlideScreenBuilder,
    GatewayScreen,
    ButtonsNavigation,
  },
  props: ['useSlideShow'],
  setup(props) {
    const instance = getCurrentInstance();
    const image = ref();
    const count = ref(0);
    const height = ref(0);
    const previewData = ref();
    const dontSupportScreen = ref();
    const gatewayScreen = ref();

    const { panelConfig } = props.useSlideShow;

    const {
      screenSize, screen, modelerContainer, node,
    } = props.useSlideShow;

    const changeSize = (size) => {
      screenSize.value = size;
    };

    const unWatch = watch(() => node.value, async (nVal, oVal) => {
      image.value = null;
      screen.value = null;
      dontSupportScreen.value = null;
      gatewayScreen.value = null;

      await Vue.nextTick();

      if (nVal && nVal !== oVal) {
        if (!acceptedNodes.includes(nVal.$type)) {
          dontSupportScreen.value = true;

          return;
        }

        if (nVal.$type.includes('Gateway')) {
          gatewayScreen.value = true;
          return;
        }

        if (nVal.screenRef) {
          const res = await getScreen(nVal.screenRef);

          screen.value = res.data;

          return;
        }

        const img = instance.proxy.$globalStore.getters['slideshow/getImage']('custom_properties.node_id', node.value.id);

        if (img) {
          image.value = {
            src: `/storage/${img.id}/${img.name}`,
            name: img.name,
            uuid: img.uuid,
          };
        }
      }
    });

    onMounted(() => {
      height.value = modelerContainer.value?.$el?.getBoundingClientRect().height;
    });

    onUnmounted(() => unWatch());

    return {
      image,
      screen,
      previewData,
      count,
      screenSize,
      changeSize,
      height,
      dontSupportScreen,
      gatewayScreen,
      node,
      panelConfig,
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
