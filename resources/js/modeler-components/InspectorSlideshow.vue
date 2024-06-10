<template>
  <div>
    <ImageUpload
      v-model="data"
      :multiple="false" />
  </div>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue';
import ImageUpload from '../sharedComponents/ImageUpload.vue';

export default defineComponent({
  components: {
    ImageUpload,
  },
  setup() {
    const data = ref([]);

    const uploadImage = () => {

    };

    onMounted(() => {
      // Example set image
      // const { id, name, original_url } = window.ProcessMaker.modeler.process.media.at(-1);

      // data.value.push({
      //   id,
      //   name,
      //   src: original_url,
      //   file: null,
      // });
    });

    return {
      data,
      uploadImage,
    };
  },
});
</script>
