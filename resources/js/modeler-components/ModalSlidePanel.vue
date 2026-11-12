<template>
  <div
    class="modal fade"
    id="slideone-modal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <Settings @close="hide" />
      </div>
    </div>
  </div>
</template>

<script>
import { onMounted, defineComponent, ref } from 'vue';
import * as bootstrap from 'bootstrap';
import Settings from './Settings.vue';

export default defineComponent({
  components: { Settings },
  setup() {
    const modal = ref();

    const show = () => modal.value.show();
    const hide = () => modal.value.hide();

    onMounted(() => {
      modal.value = new bootstrap.Modal(document.getElementById('slideone-modal'), {
        backdrop: true,
      });
    });

    return { show, hide };
  },
});
</script>
