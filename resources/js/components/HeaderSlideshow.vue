<template>
  <div
    class="d-flex justify-content-between align-items-center py-3 px-4 bg-dark shadow-sm">
    <div class="flex items-center space-x-3">
      <ILogo class="h-6 w-6" />

      <Dropdown
        class="text-gray-500 w-36 rounded-r-md font-semibold rounded-l-md bg-yellow-100 text-sm"
        v-model="alternativeModel"
        :options="options"
        @change="onChange">
        <template #input="{toogleShow}">
          <button
            @click.prevent.stop="toogleShow"
            class="flex w-full justify-center items-center space-x-3
              py-1 px-2 ring-1 ring-inset ring-gray-300
              focus:ring-2 focus:ring-inset focus:ring-primary-400 rounded-l-md rounded-r-md">
            <span>{{ flowModel?.label || flowModel?.value || $t('Alternative A') }}</span>

            <i class="fas fa-chevron-down" />
          </button>
        </template>
      </Dropdown>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue';
import { ILogo } from '../icons';
import Dropdown from '../sharedComponents/Dropdown.vue';

export default defineComponent({
  components: {
    ILogo,
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

    const options = ref([
      {
        label: 'Alternative A',
        value: 'A',
      },
      {
        label: 'Alternative B',
        value: 'B',
      },
    ]);

    const alternativeModel = ref(options.value[0]);

    const onChange = (e) => {
      alternativeModel.value = e;

      alternative.value = e.value;
    };

    return { alternativeModel, options, onChange };
  },
});
</script>
