<template>
  <div class="flex w-full items-center space-x-4 px-4 text-sm">
    <button
      @click="onPrev"
      type="button"
      class="rounded-l rounded-r bg-gray-600 px-10 py-2 font-semibold
        text-white shadow-sm hover:bg-gray-500
        focus-visible:outline focus-visible:outline-2
        focus-visible:outline-offset-2 focus-visible:outline-gray-600">
      {{ $t('Back') }}
    </button>

    <button
      @click="onNext"
      type="button"
      class="rounded-l rounded-r bg-blue-500 px-10 py-2 font-semibold
      text-white shadow-sm hover:bg-blue-400
        focus-visible:outline focus-visible:outline-2
        focus-visible:outline-offset-2 focus-visible:outline-blue-600">
      {{ $t('Next') }}
    </button>
  </div>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
  emits: ['prev', 'next'],
  setup(props, { emit }) {
    const onPrev = () => {
      emit('prev');
    };

    const onNext = () => {
      emit('next');
    };

    return {
      onPrev,
      onNext,
    };
  },
});
</script>
