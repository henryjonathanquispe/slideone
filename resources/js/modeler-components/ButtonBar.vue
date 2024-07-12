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
import {
  defineComponent, ref, nextTick, onMounted, onUnmounted, getCurrentInstance,
} from 'vue';
import ModalSlidePanel from './ModalSlidePanel.vue';
import ISlideshow from '../icons/ISlideshow.vue';
import Teleport from '../sharedComponents/Teleport.vue';
import { $getModeler, getImages, removeImage } from '../utils/slideshowApi';

export default defineComponent({
  components: {
    ISlideshow,
    ModalSlidePanel,
    Teleport,
  },
  setup() {
    const instance = getCurrentInstance();
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

    const getImage = (id) => instance.proxy.$globalStore.getters['slideshow/getImage']('custom_properties.node_id', id);

    const removeNode = async (e) => {
      // Removed the slideShow image
      const dataImg = getImage(e.definition.id);

      if (dataImg) {
        const response = await removeImage(dataImg.uuid);

        if (response.success) {
          await getImages();
        }
      }
    };

    onMounted(() => {
      nextTick(() => {
        $getModeler().$on('removeNode', removeNode);
      });
    });

    onUnmounted(() => {
      $getModeler().$off('removeNode', removeNode);
    });

    return {
      modal,
      count,
      openModal,
      closeModal,
      removeImage,
    };
  },
});
</script>
