<template>
  <div
    class="d-flex justify-content-between align-items-center py-3 px-4 bg-dark shadow-sm">
    <div class="flex items-center">
      <PmIcon class="h-6 w-6" />
    </div>

    <div class="flex h-6 items-center">
      <input
        @change="onClick"
        id="comments"
        aria-describedby="comments-description"
        name="comments"
        type="checkbox"
        v-model="toogle"
        :true-value="true"
        :false-value="false"
        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
    </div>
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue';
import PmIcon from './icons/PmIcon.vue';

export default defineComponent({
  components: {
    PmIcon,
  },
  emits: ['without-permission'],
  setup(props, { emit }) {
    const toogle = ref(false);

    const onClick = () => {
      emit('without-permission', toogle.value);
    };

    return {
      onClick,
      toogle,
    };
  },
});
</script>
