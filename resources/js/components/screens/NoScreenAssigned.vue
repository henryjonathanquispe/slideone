<template>
  <div
    class="flex text-gray-500 justify-center text-center items-center bg-white w-100">
    <div class="flex flex-col items-center">
      <INoScreen class="h-40 w-40" />

      <span class="text-2xl font-semibold pb-2">
        {{ $t("No screen assigned") }}
      </span>

      <span>
        {{ $t("This is a screen placeholder") }}
      </span>
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue';
import { INoScreen } from '../../icons';

export default defineComponent({
  components: {
    INoScreen,
  },
});
</script>
