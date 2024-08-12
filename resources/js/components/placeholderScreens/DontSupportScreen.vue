<template>
  <div
    class="flex text-gray-500 justify-center text-center items-center bg-white w-100">
    <div class="flex flex-col items-center">
      <IDontSupport class="h-40 w-40" />
      <span class="text-2xl font-semibold pb-2">
        {{ $t("This asset doesn't support preview") }}
      </span>
      <span>
        {{ $t("This is a screen placeholder") }}
      </span>

      <CardDocumentation :node="node" :editable="false" />
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue';
import { IDontSupport } from '../../icons';
import CardDocumentation from './CardDocumentation.vue';

export default defineComponent({
  props: {
    useSlideShow: {
      type: Object,
      default: () => {},
    },
    useNavigation: {
      type: Object,
      default: () => {},
    },
  },
  components: {
    IDontSupport,
    CardDocumentation,
  },
  setup(props) {
    const { node } = props.useSlideShow;
    return { node };
  },
});
</script>
