<template>
  <div
    class="d-flex justify-content-between align-items-center py-3 px-4 bg-dark shadow-sm">
    <div class="flex items-center space-x-3">
      <img :src="src" alt="processmaker-logo" class="h-7">
    </div>

    <Dropdown
      v-if="showAlternatives"
      v-model="alternativeModel"
      class="text-gray-500 w-36 rounded-r-md font-semibold rounded-l-md bg-slate-200 text-sm"
      :options="options"
      @change="onChange">
      <template #input="{toogleShow}">
        <button
          @click.prevent.stop="toogleShow"
          class="flex w-full justify-center items-center space-x-3
            py-1 px-2 ring-1 ring-inset ring-gray-300
            focus:ring-2 focus:ring-inset focus:ring-primary-400 rounded-l-md rounded-r-md">
          <span>{{ alternativeModel?.label || alternativeModel?.value || $t('Alternative A') }}</span>

          <i class="fas fa-chevron-down" />
        </button>
      </template>
    </Dropdown>
  </div>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue';
import { t } from 'i18n';
import Dropdown from '../sharedComponents/Dropdown.vue';
import { getPublishedVersion, hasRestriction } from '../utils/slideshowApi';

export default defineComponent({
  components: {
    Dropdown,
  },
  props: {
    useSlideShow: {
      type: Object,
      default: () => {},
    },
  },
  setup(props) {
    const { alternative } = props.useSlideShow;
    const showAlternatives = ref(false);
    const src = '/img/processmaker.png';

    const options = ref([
      {
        label: t('Alternative A'),
        value: 'A',
      },
      {
        label: t('Alternative B'),
        value: 'B',
      },
    ]);

    const alternativeModel = ref(options.value[0]);

    const onChange = (e) => {
      alternative.value = e.value;
    };

    const buildAlternatives = () => {
      const publishedVersion = getPublishedVersion();
      if (publishedVersion === 'AB') {
        showAlternatives.value = true;

        return;
      }

      alternative.value = publishedVersion || 'A';
    };

    onMounted(() => {
      buildAlternatives();
    });

    return {
      alternativeModel,
      options,
      onChange,
      showAlternatives,
      src,
    };
  },
});
</script>
