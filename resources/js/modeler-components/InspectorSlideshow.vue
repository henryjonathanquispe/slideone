<template>
  <div class="col-span-full">
    <label for="cover-photo" class="block">{{ $t("Upload an Image for Slideshow") }}</label>

    <ImageUpload
      :disabled="disabled"
      v-model="data"
      @add="addImage"
      @remove="removeImage"
      :multiple="false" />
  </div>
</template>

<script>
import {
  defineComponent, ref, computed, getCurrentInstance, onMounted,
} from 'vue';
import ImageUpload from '../sharedComponents/ImageUpload.vue';

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
    const getImage = () => instance.proxy.$globalStore.getters['slideshow/getImage']('custom_properties.node_id', node.value.id);

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
    const addImage = async (el) => {
      disabled.value = true;

      const response = await apiClient.put(`/slideshow/images/${modeler.process.id}`, {
        imageSlideshow: {
          url: el.file,
          name: el.name,
          type: 'task',
          node_id: node.value.id,
          uuid: null,
        },
      });

      disabled.value = false;

      if (response.errors) {
        alert(response.errors, 'danger');
      }

      // Update the uuid in image model
      if (response.data?.success) {
        await getImages();

        updateImage();
      }
    };

    // Remove Image with API slideshow
    const removeImage = async (index, el) => {
      const response = await apiClient.delete(`/slideshow/images/${el.uuid}`);

      if (response.errors) {
        alert(response.errors, 'danger');
      }

      await getImages();

      updateImage();
    };

    // Update the local image if exists the image in the store
    onMounted(() => updateImage());

    return {
      data,
      removeImage,
      addImage,
      disabled,
      getImages,
    };
  },
});
</script>
