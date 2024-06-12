<template>
  <div class="col-span-full">
    <label for="cover-photo" class="block">{{ $t("Upload an Image for Slideshow") }}</label>
    <ImageUpload
      :disable-button="data.length == 0"
      v-model="data"
      :multiple="true" />
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue';
import ImageUpload from '../sharedComponents/ImageUpload.vue';

export default defineComponent({
  components: {
    ImageUpload,
  },
  setup() {
    const data = ref([]);

    return {
      data,
    };
  },
});
</script>
