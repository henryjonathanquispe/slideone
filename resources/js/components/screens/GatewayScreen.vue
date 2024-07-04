<template>
  <div
    class="flex text-gray-500 justify-center text-center items-center bg-white w-100">
    <div class="flex flex-col items-center">
      <IGateway class="h-40 w-40" />
      <span class="text-2xl font-semibold pb-2">
        {{ $t(props.title) }}
      </span>
      <span>
        {{ $t(props.subtitle) }}
      </span>
      <Dropdown
        class="text-gray-500 font-semibold w-80 pt-8"
        v-model="flowModel"
        :options="localOptions"
        @change="changeFlow">
        <template #input="{toogleShow}">
          <button
            @click.prevent.stop="toogleShow"
            class="flex w-full justify-between items-center py-2 px-3 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-400 rounded-l-md rounded-r-md">
            <IGatewayRect class="w-5 h-5" />

            <span>{{ flowModel?.label || flowModel?.value || $t('Select Gateway response') }}</span>

            <i class="fas fa-chevron-down" />
          </button>
        </template>
      </Dropdown>
    </div>
  </div>
</template>

<script>
import {
  defineComponent, computed, ref, onMounted,
} from 'vue';
import { IGateway, IGatewayRect } from '../../icons';
import Dropdown from '../../sharedComponents/Dropdown.vue';

export default defineComponent({
  props: {
    title: {
      type: String,
      default: () => '',
    },
    subtitle: {
      type: String,
      default: () => '',
    },
    options: {
      type: Array,
      default: () => [],
    },
    useNavigation: {
      type: Object,
      default: () => {},
    },
  },
  components: {
    IGateway,
    IGatewayRect,
    Dropdown,
  },
  setup(props) {
    const localOptions = computed(() => props.options);
    const flowModel = ref();

    const { gatewayFlow } = props.useNavigation;

    const changeFlow = (e) => {
      flowModel.value = e;

      gatewayFlow.value = e.value;
    };

    onMounted(() => {
      flowModel.value = localOptions.value[0];
    });

    return {
      props,
      localOptions,
      changeFlow,
      flowModel,
    };
  },
});
</script>
