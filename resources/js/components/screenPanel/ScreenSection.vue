<template>
  <div class="flex flex-col max-h-full grow">
    <HeaderScreenSection :use-slide-show="useSlideShow" @changeSize="changeSize" />
    <div
      class="transition-all flex justify-center content-start"
      :style="{ 'height': height - 30 + 'px' }">
      <Transition
        name="fade"
        mode="out-in">
        <div
          v-if="screen"
          class="w-full h-full ">
          <span
            v-if="tabs.length == 1 "
            class="flex text-gray-500 font-semibold px-3 py-2 w-full"> {{ screen.title }} </span>

          <Dropdown
            v-else
            class="text-gray-500 font-semibold"
            v-model="currentPage"
            :options="tabs"
            @change="changePage" />

          <div class="bg-white">
            <vue-form-renderer
              class="bg-white px-3 py-2"
              ref="formRenderer"
              :key="count"
              :device-screen="screenSize"
              v-model="previewData"
              :config="screen.config"
              :computed="screen.computed"
              :custom-css="screen.custom_css"
              :watchers="screen.watchers"
              :show-errors="true" />
          </div>
        </div>

        <img
          v-else-if="image"
          class="bg-gray-50 max-h-full h-auto box-border object-contain"
          :src="image.file || image.src"
          :key="count"
          alt="">

        <NoScreenAssigned
          v-else
          class="h-100 pb-32" />
      </Transition>
    </div>
  </div>
</template>

<script>
import {
  defineComponent, ref, getCurrentInstance, computed, watch, onUnmounted, onMounted,
} from 'vue';
import { VueFormRenderer } from 'vue-form-renderer';
import Vue from 'Vue';
import NoScreenAssigned from '../NoScreenAssigned.vue';
import { getScreen } from '../../utils/slideshowApi';
import HeaderScreenSection from './HeaderScreenSection.vue';
import Dropdown from '../../sharedComponents/Dropdown.vue';

const buildTabs = (screen) => {
  const res = [];
  if (screen.value.config instanceof Array) {
    screen.value.config.forEach((page, index) => {
      res.push({
        label: page.name,
        value: index,
        order: page.order,
      });
    });
  }

  return res;
};

export default defineComponent({
  emits: ['close'],
  components: {
    VueFormRenderer,
    NoScreenAssigned,
    HeaderScreenSection,
    Dropdown,
  },
  props: ['useSlideShow'],
  setup(props) {
    const instance = getCurrentInstance();
    const image = ref();
    const count = ref(0);
    const height = ref(0);
    const previewData = ref();
    const formRenderer = ref();
    const tabs = ref([]);
    const currentPage = ref();

    const { screenSize, screen, modelerContainer } = props.useSlideShow;

    const changeSize = (size) => {
      screenSize.value = size;
      count.value += 1;
    };

    const unWatch = watch(() => props.useSlideShow.node.value, async (nVal, oVal) => {
      image.value = null;
      screen.value = null;

      await Vue.nextTick();

      if (nVal && nVal !== oVal) {
        const img = instance.proxy.$globalStore.getters['slideshow/getImage']('custom_properties.node_id', props.useSlideShow.node.value.id);

        if (img) {
          image.value = {
            src: `/storage/${img.id}/${img.name}`,
            name: img.name,
            uuid: img.uuid,
          };

          return;
        }

        if (nVal.screenRef) {
          const res = await getScreen(nVal.screenRef);

          screen.value = res.data;

          tabs.value = buildTabs(screen);

          currentPage.value = tabs.value[0];
        }
      }
    });

    const changePage = (tab) => {
      formRenderer.value.setCurrentPage(tab.value);
      currentPage.value = tab;
    };

    onMounted(() => {
      height.value = modelerContainer.value?.$el?.getBoundingClientRect().height;
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
      height,
      formRenderer,
      tabs,
      changePage,
      currentPage,
    };
  },
});
</script>

<style>
.btn-outline-gray {
  border: 1px solid #cdddee !important;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
