<template>
  <div class="h-100">
    <div
      class="flex justify-between items-center py-2 px-3 text-gray-500 bg-white shadow-sm border-b">
      <div class="flex flex-row items-center space-x-2">
        <IEye class="h-4 w-4" />

        <span>{{ $t("Preview") }}</span>
      </div>

      <div class="flex items-center">
        <div class="flex justify-content space-x-1">
          <button
            @click="onExpand"
            type="button"
            class="btn border flex items-center space-x-3 rounded-md shadow-sm text-gray-500 capitalize">
            <IFullScreen class="h-5 w-5" />

            <span>{{ $t("Full Screen") }}</span>
          </button>

          <button @click="onClose" type="button" class="btn hover:opacity-75">
            <IClose class="h-4 w-4" />
          </button>
        </div>
      </div>
    </div>

    <div
      class="h-100 d-flex flex-column align-items-center justify-content-center">
      <NoScreenAssigned class="h-100 pb-32" />
    </div>
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue';
import { VueFormRenderer } from 'vue-form-renderer';
import NoScreenAssigned from './NoScreenAssigned.vue';
import { IEye, IFullScreen, IClose } from '../icons';

export default defineComponent({
  emits: ['expand', 'close'],
  props: ['useSlide'],
  components: {
    VueFormRenderer,
    NoScreenAssigned,
    IEye,
    IFullScreen,
    IClose,
  },
  setup(props, { emit }) {
    const { data } = props.useSlide;

    const screen = ref({});

    const onExpand = () => {
      emit('expand');
    };

    const onClose = () => {
      emit('close');
    };

    return {
      onClose,
      onExpand,
      data,
      screen,
    };
  },
});
</script>

<style>
.btn-outline-gray {
  border: 1px solid #cdddee !important;
}
</style>
