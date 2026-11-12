<template>
  <div>
    <div
      v-b-tooltip.hover
      @click="openModal"
      :title="$t('SlideOne pero para el otro tenant')"
      class="py-1 px-2 rounded-sm text-gray-500 hover:bg-gray-200 hover:text-gray-500">
      <i class="fp-slideshow h-4 w-4" />
    </div>
    <Teleport :key="count" to="body">
      <ModalSlidePanel ref="modal" />
    </Teleport>
  </div>
</template>

<script>
import {
  defineComponent, ref, nextTick,
} from 'vue';
import ModalSlidePanel from './ModalSlidePanel.vue';
import Teleport from '../sharedComponents/Teleport.vue';

export default defineComponent({
  components: {
    ModalSlidePanel,
    Teleport,
  },
  setup() {
    const modal = ref();
    const count = ref(0);

    const openModal = () => {
      count.value++;
      nextTick(() => {
        modal.value.show();
      });
    };

    return {
      modal,
      count,
      openModal,
    };
  },
});
</script>
