<template>
  <div class="flex w-full items-center space-x-4 px-4 text-sm">
    <button
      v-if="props.showBackButton"
      @click="onPrev"
      type="button"
      class="rounded-l rounded-r bg-gray-600 px-10 py-2 font-semibold
        text-white shadow-sm hover:bg-gray-500
        focus-visible:outline focus-visible:outline-2
        focus-visible:outline-offset-2 focus-visible:outline-gray-600">
      {{ $t('Reset') }}
    </button>

    <button
      v-if="props.showNextButton"
      @click="onNext"
      type="button"
      class="rounded-l rounded-r bg-blue-500 px-10 py-2 font-semibold
      text-white shadow-sm hover:bg-blue-400
        focus-visible:outline focus-visible:outline-2
        focus-visible:outline-offset-2 focus-visible:outline-blue-600">
      {{ $t('Next') }}
    </button>

    <button
      v-if="props.showStartButton && startButtonOptions.length && startButtonOptions.length == 1"
      @click="onStart(startButtonOptions[0])"
      type="button"
      class="rounded-l rounded-r bg-green-600 px-4 py-2 font-semibold
      text-white shadow-sm hover:bg-green-700
        focus-visible:outline focus-visible:outline-2
        focus-visible:outline-offset-2 focus-visible:outline-green-800">
      {{ $t('Start process') }}
    </button>

    <Dropdown
      v-if="props.showStartButton && startButtonOptions.length && startButtonOptions.length > 1"
      v-model="startProcess"
      @change="onChange"
      :options="startButtonOptions">
      <template #input="{toogleShow}">
        <button
          @click.prevent.stop="toogleShow"
          type="button"
          class="flex justify-between items-center rounded-l rounded-r bg-green-600 px-4 py-2
            font-semibold text-white shadow-sm hover:bg-green-700
            focus-visible:outline focus-visible:outline-2 space-x-2
            focus-visible:outline-offset-2 focus-visible:outline-green-800">
          <span>{{ $t('Start process') }}</span>

          <i class="fas fa-chevron-down" />
        </button>
      </template>
    </Dropdown>
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue';
import Dropdown from '../sharedComponents/Dropdown.vue';

export default defineComponent({
  components: {
    Dropdown,
  },
  props: {
    showBackButton: {
      type: Boolean,
      default: () => true,
    },
    showNextButton: {
      type: Boolean,
      default: () => true,
    },
    showStartButton: {
      type: Boolean,
      default: () => true,
    },
    startButtonOptions: {
      type: Array,
      default: () => [],
    },
  },
  emits: ['prev', 'next', 'start'],
  setup(props, { emit }) {
    const startProcess = ref();

    const onPrev = () => {
      emit('prev');
    };

    const onNext = () => {
      emit('next');
    };

    const onStart = (option) => {
      emit('start', option);
    };

    const onChange = (option) => {
      emit('start', option);
    };

    return {
      onPrev,
      onNext,
      onStart,
      onChange,
      props,
      startProcess,
    };
  },
});
</script>
