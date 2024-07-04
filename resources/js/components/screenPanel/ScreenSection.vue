<template>
  <div class="flex flex-col max-h-full grow">
    <HeaderScreenSection ref="refHeader" :use-slide-show="useSlideShow" @changeSize="changeSize" />
    <div
      class="transition-all flex flex-col content-start"
      :style="{ 'height': (height - heightNavButtons - 20) + 'px' }">
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
          :options="gatewayOptions"
          :use-navigation="useNavigation"
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
        ref="refNav"
        v-if="panelConfig == 'right-panel'"
        class="py-2 justify-center"
        :show-back-button="hasPrevNode"
        :show-next-button="hasNextNode"
        @next="onNext"
        @prev="onPrev" />
    </div>
  </div>
</template>

<script>
import {
  defineComponent, ref, getCurrentInstance, watch, onUnmounted, onMounted, computed,
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
  props: ['useSlideShow', 'useNavigation'],
  emit: ['prev', 'next'],
  setup(props, { emit }) {
    const instance = getCurrentInstance();
    const image = ref();
    const count = ref(0);
    const height = ref(0);
    const previewData = ref();
    const dontSupportScreen = ref();
    const gatewayScreen = ref();
    const gatewayOptions = ref([]);
    const refNav = ref();
    const refHeader = ref();
    const {
      screenSize, panelConfig, screen, modelerContainer, node, findMapElement,
    } = props.useSlideShow;

    const {
      hasPrevNode, hasNextNode, currentElement, getCurrentGatewayOptions,
    } = props.useNavigation;

    const changeSize = (size) => {
      screenSize.value = size;
    };

    const buildGatewayOptions = (flows) => flows.map((e) => ({
      label: e.name || e.id,
      value: e.id,
    }));

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

          const flows = getCurrentGatewayOptions().map((e) => findMapElement(e));

          gatewayOptions.value = buildGatewayOptions(flows);
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

    const heightNavButtons = computed(() => refNav.value?.$el?.getBoundingClientRect().height);

    const onPrev = () => emit('prev');

    const onNext = () => emit('next');

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
      refNav,
      refHeader,
      heightNavButtons,
      onPrev,
      onNext,
      hasPrevNode,
      hasNextNode,
      gatewayOptions,
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
