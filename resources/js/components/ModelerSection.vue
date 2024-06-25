<template>
  <div class="flex flex-col justify-between border w-100 h-100 max-h-full">
    <div
      class="flex justify-between align-items-center py-2 px-3 text-muted bg-white shadow-sm">
      <div class="flex align-items-center space-x-2">
        <IMap class="w-4 h-4" />

        <div class="lh-1">
          <span> {{ $t('Process Map') }}</span>
        </div>
      </div>

      <div class="flex items-center" />
    </div>

    <div class="h-100">
      <ModelerApp id="modeler-app" @clickNode="clickNode" />
    </div>

    <div class="modal-footer flex justify-between items-center">
      <button type="button" class="btn bg-secondary text-white px-5">
        {{ $t('Back') }}
      </button>

      <button type="button" class="btn bg-primary text-white px-5">
        {{ $t('Next') }}
      </button>
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue';
import ModelerApp from './ModelerApp';
import { IMap } from '../icons';

export default defineComponent({
  emits: ['expand', 'close', 'clickNode'],
  props: ['useSlideShow'],
  components: {
    ModelerApp,
    IMap,
  },
  setup(props, { emit }) {
    const onExpand = () => {
      emit('expand');
    };

    const onClose = () => {
      emit('close');
    };

    const clickNode = (e) => {
      emit('clickNode', e);
    };

    return {
      onClose,
      onExpand,
      clickNode,
    };
  },
});
</script>

<style>
.btn-outline-gray {
  border: 1px solid var(--borders, #cdddee) !important;
}
</style>
