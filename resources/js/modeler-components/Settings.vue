<template>
  <div class="d-flex flex-column text-gray-500">
    <div class="modal-header p-3 d-flex align-items-center justify-content-between bg-light">
      <span class="text-xl tracking-tight font-semibold text-[#556271]">
        {{ $t('SlideOne') }}
      </span>
      <span @click="onClose">
        <i class="fp-close hover:opacity-75 hover:cursor-pointer" />
      </span>
    </div>
    <div class="modal-body">
      <p class="text-sm text-[#556271]">
        {{ $t('Plugin test modal from the modeler status bar.') }}
      </p>
      <p class="text-sm text-muted">
        {{ $t('Test route') }}: <code>/slideone/test</code>
      </p>
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
  emits: ['close'],
  setup(props, { emit }) {
    const onClose = () => emit('close');

    return { onClose };
  },
});
</script>

<style scoped>
div.bg-light {
  background-color: #f2f8fe !important;
}
</style>
