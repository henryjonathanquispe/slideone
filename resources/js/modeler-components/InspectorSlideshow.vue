<template>
  <div>
    <ImageUpload
      v-model="data"
      :multiple="false" />
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
      data
    };
  },
});
</script>
