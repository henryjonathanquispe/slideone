<template>
  <div class="col-span-full">
    <label for="cover-photo" class="block">{{ $t("Upload an Image for Slideshow") }}</label>

    <ImageUpload
      :disabled="disabled"
      v-model="data"
      @add="add"
      @remove="remove"
      :multiple="false" />
  </div>
</template>

<script>
import {
  defineComponent, ref, computed, getCurrentInstance, onMounted,
} from 'vue';
import ImageUpload from '../sharedComponents/ImageUpload.vue';
import { removeImage, addImage, getCurrentAlternative } from '../utils/slideshowApi';

const { apiClient, alert, modeler } = window.ProcessMaker;

export default defineComponent({
  components: {
    ImageUpload,
  },
  setup() {
    const instance = getCurrentInstance();
    const data = ref([]);
    const disabled = ref(false);
    const node = computed(() => instance.proxy.$root.$children[0].$refs.modeler.highlightedNode.definition);

    // Get images using the APIS & update the store module vuex
    const getImages = async () => {
      const response = await apiClient.get(`/slideshow/images/${modeler.process.id}`);

      if (response.data) {
        instance.proxy.$globalStore.commit('slideshow/setImages', response.data[0].media);
      }
    };

    // Get the image from globalstore module slideshow
    const getImage = () => instance.proxy.$globalStore.getters['slideshow/getImageByProps']({
      'custom_properties.node_id': node.value.id,
      'custom_properties.alternative': getCurrentAlternative(),
    });

    // Update the local image with the images in module vuex
    const updateImage = () => {
      const image = getImage();

      if (image) {
        data.value = [{
          src: image.original_url,
          name: image.name,
          uuid: image.uuid,
        }];

        return;
      }

      data.value = [];
    };

    // Add Image with API slideshow
    const add = async (el) => {
      disabled.value = true;

      const response = await addImage({
        url: el.file,
        name: el.name,
        type: 'task',
        node_id: node.value.id,
        alternative: getCurrentAlternative(),
        uuid: null,
      });

      disabled.value = false;

      // Update the uuid in image model
      if (response.data?.success) {
        await getImages();

        updateImage();
      }
    };

    // Remove Image with API slideshow
    const remove = async (index, el) => {
      await removeImage(el.uuid);

      await getImages();

      updateImage();
    };

    // Update the local image if exists the image in the store
    onMounted(() => updateImage());

    return {
      data,
      remove,
      add,
      disabled,
      getImages,
    };
  },
});
</script>
