<template>
  <div class="col-span-full text-gray-600">
    <label class="block font-semibold">{{ $t("Placeholder for Slideshow") }}</label>

    <label v-if="!data.length" class="block">{{ $t("Upload an Image for Slideshow") }}</label>

    <ImageUpload
      :disabled="disabled"
      v-model="data"
      :max-size="maxSize"
      @add="add"
      @error="showError"
      @remove="remove"
      :multiple="false">
      <template #placeholder="{multiple, onFileChange}">
        <label
          for="file-upload"
          class="flex items-center mb-0 text-sm cursor-pointer space-x-1">
          <IPlus />

          <span class="font-semibold">{{ $t("Drag or click here") }} </span> <span>{{ $t('to upload') }}</span>

          <input
            :multiple="multiple"
            id="file-upload"
            name="file-upload"
            type="file"
            accept="image/png, image/jpeg, image/jpg, image/gif"
            @change="onFileChange"
            class="sr-only">
        </label>
      </template>
    </ImageUpload>

    <span
      v-if="errorMessage"
      class="block text-xs pt-2 text-red-500">
      {{ $t(errorMessage) }}
    </span>

    <span
      v-if="!data.length"
      class="block text-xs pt-2">
      {{ $t(messages.sizes) }}
    </span>
  </div>
</template>

<script>
import {
  defineComponent, ref, computed, getCurrentInstance, onMounted,
} from 'vue';
import { t } from 'i18n';
import ImageUpload from '../sharedComponents/ImageUpload.vue';
import { removeImage, addImage, getCurrentAlternative } from '../utils/slideshowApi';
import { IPlus } from '../icons';

const { apiClient, modeler, alert } = window.ProcessMaker;
const maxSize = 2000000;

export default defineComponent({
  components: {
    ImageUpload,
    IPlus,
  },
  setup() {
    const instance = getCurrentInstance();
    const data = ref([]);
    const messages = ref({
      sizes: 'PNG, JPG, JPEG, GIF images, 2 Mb max size.',
      invalid: 'Image not valid',
      maxFiles: 'It is not possible to include multiple files',
    });
    const errorMessage = ref('');

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
          name: image?.file_name || image?.name,
          uuid: image.uuid,
          size: image.size,
        }];

        return;
      }

      data.value = [];
    };

    // Add Image with API slideshow
    const add = async (el) => {
      errorMessage.value = false;

      disabled.value = true;

      try {
        const response = await addImage({
          url: el.file,
          file_name: el.name,
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
      } catch (e) {
        alert(e, 'danger');

        data.value = [];
        disabled.value = false;
      }
    };

    // Remove Image with API slideshow
    const remove = async (index, el) => {
      await removeImage(el.uuid);

      await getImages();

      updateImage();

      alert(t('The image was deleted'), 'success');
    };

    const showError = (error) => {
      error.invalid && (errorMessage.value = messages.value.invalid);

      error.maxFiles && (errorMessage.value = messages.value.maxFiles);
    };

    // Update the local image if exists the image in the store
    onMounted(() => updateImage());

    return {
      data,
      remove,
      add,
      disabled,
      getImages,
      messages,
      showError,
      maxSize,
      errorMessage,
    };
  },
});
</script>
