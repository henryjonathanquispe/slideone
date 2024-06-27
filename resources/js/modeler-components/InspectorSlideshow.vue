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
  setup(props, context) {
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

    // Remove Image with API slideshow
    const removeImage = async (index, el) => {
      const response = await apiClient.delete(`/slideshow/images/${modeler.process.id}`, {
        data: { uuid: el.uuid },
      });

      if (response.errors) {
        alert(response.errors, 'danger');
      }

      getImages();
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
      if (response.data?.data) {
        data.value[0].uuid = response.data.data.uuid;

        getImages();
      }
    };

    const getImage = () => instance.proxy.$globalStore.getters['slideshow/getImage']('custom_properties.node_id', node.value.id);

    onMounted(() => {
      const image = getImage();

      image && data.value.push({
        src: `/storage/${image.id}/${image.name}`,
        name: image.name,
        uuid: image.uuid,
      });
    });

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
