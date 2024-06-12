<template>
  <a
    v-b-tooltip.hover
    :href="url"
    target="_blank"
    :title="$t('Slideshow')"
    class=" py-1 px-2 rounded-sm text-gray-500 hover:bg-gray-200 hover:text-gray-500">
    <ISlideshow />
  </a>
</template>

<script>
import ISlideshow from '../icons/ISlideshow.vue';

export default {
  components: {
    ISlideshow,
  },
  data() {
    return {
      url: '/slideshow/main',
    };
  },
};
</script>
