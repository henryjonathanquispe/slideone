<template>
  <div
    class="d-flex justify-content-between align-items-center py-3 px-4 bg-dark shadow-sm">
    <div class="flex items-center">
      <ILogo class="h-6 w-6" />
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue';
import { ILogo } from '../icons';

export default defineComponent({
  components: {
    ILogo,
  },
  setup() {
    return { };
  },
});
</script>
