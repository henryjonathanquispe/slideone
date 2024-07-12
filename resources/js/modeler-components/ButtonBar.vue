<template>
  <div>
    <div
      v-b-tooltip.hover
      @click="openModal"
      target="_blank"
      :title="$t('Slideshow')"
      class=" py-1 px-2 rounded-sm text-gray-500 hover:bg-gray-200 hover:text-gray-500">
      <ISlideshow />
    </div>
    <Teleport :key="count" to="body">
      <ModalSlidePanel ref="modal" />
    </Teleport>
  </div>
</template>

<script>
import { defineComponent, ref, nextTick } from 'vue';
import ModalSlidePanel from './ModalSlidePanel.vue';
import ISlideshow from '../icons/ISlideshow.vue';
import Teleport from '../sharedComponents/Teleport.vue';

export default defineComponent({
  components: {
    ISlideshow,
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

    const closeModal = () => {
      modal.value.hide();
    };

    return {
      modal,
      count,
      openModal,
      closeModal,
    };
  },
});
</script>
