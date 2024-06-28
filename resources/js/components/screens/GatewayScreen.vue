<template>
  <div
    class="flex text-gray-500 justify-center text-center items-center bg-white w-100">
    <div class="flex flex-col items-center">
      <IGateway class="h-40 w-40" />
      <span class="text-2xl font-semibold pb-2">
        {{ $t(props.title) }}
      </span>
      <span>
        {{ $t(props.subtitle) }}
      </span>
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue';
import { IGateway } from '../../icons';

export default defineComponent({
  props: {
    title: {
      type: String,
      default: () => '',
    },
    subtitle: {
      type: String,
      default: () => '',
    },
  },
  components: {
    IGateway,
  },
  setup(props) {
    return { props };
  },
});
</script>
