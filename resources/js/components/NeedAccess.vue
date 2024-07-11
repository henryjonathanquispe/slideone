<template>
  <div
    class="flex text-gray-500 justify-center text-center items-center bg-white w-100">
    <div class="flex flex-col items-center">
      <INeedAccess class="h-40 w-40" />
      <span class="text-2xl font-semibold pb-2">
        {{ $t("You Need Access") }}
      </span>
      <span>
        {{ $t("The link has been disabled, contact us if you need acces to it again") }}
      </span>
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue';
import { INeedAccess } from '../icons';

export default defineComponent({
  components: {
    INeedAccess,
  },
});
</script>
