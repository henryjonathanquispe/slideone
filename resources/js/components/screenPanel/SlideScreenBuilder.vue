<template>
  <div
    v-if="screenModel"
    class="w-full h-full">
    <span
      v-if="tabs.length == 1 "
      class="flex text-gray-500 font-semibold px-3 py-2 w-full"> {{ screenModel.title }} </span>

    <Dropdown
      v-else
      class="text-gray-500 font-semibold"
      v-model="currentPage"
      :options="tabs"
      @change="changePage" />

    <div class="bg-white overflow-y-auto">
      <vue-form-renderer
        class="bg-white px-3 py-2"
        ref="formRenderer"
        :key="count"
        :device-screen="screenSize"
        v-model="previewData"
        :config="screenModel.config"
        :computed="screenModel.computed"
        :custom-css="screenModel.custom_css"
        :watchers="screenModel.watchers"
        :show-errors="true" />
    </div>
  </div>
</template>

<script>

import { VueFormRenderer } from 'vue-form-renderer';
import {
  defineComponent, computed, ref, onMounted, watch, onUnmounted,
} from 'vue';
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
  components: {
    Dropdown,
    VueFormRenderer,
  },
  props: {
    screen: {
      type: Object,
      default: () => null,
    },
    screenSize: {
      type: String,
      default: () => 'desktop',
    },
  },
  emits: ['update:screen'],
  setup(props, { emit }) {
    const tabs = ref([]);
    const formRenderer = ref();
    const count = ref(0);
    const currentPage = ref();

    const screenModel = computed({
      get() {
        return props.screen;
      },
      set(val) {
        emit('update:screen', val);
      },
    });

    const changePage = (tab) => {
      formRenderer.value.setCurrentPage(tab.value);
    };

    const unWatch = watch(() => props.screenSize, () => {
      count.value += 1;
    });

    onMounted(() => {
      tabs.value = buildTabs(screenModel);

      currentPage.value = tabs.value[0];
    });

    onUnmounted(() => {
      unWatch();
    });

    return {
      screenModel,
      props,
      tabs,
      currentPage,
      formRenderer,
      count,
      changePage,
    };
  },
});
</script>
