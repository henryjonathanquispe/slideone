<script>
import { defineComponent } from "vue";
import ModelerApp from "./ModelerApp";

export default defineComponent({
  emits: ["expand", "close", "clickNode"],
  props: ["useSlideShow"],
  components: {
    ModelerApp,
  },
  setup(props, { emit }) {
    const onExpand = () => {
      emit("expand");
    };

    const onClose = () => {
      emit("close");
    };

    const clickNode = (e) => {
      console.log("BREAKPOINT::: MODELER SECTION");
      emit("clickNode", e);
    };

    return {
      onClose,
      onExpand,
      clickNode,
    };
  },
});
</script>

<template>
  <div class="d-flex flex-column justify-content-between border w-100">
    <div
      class="d-flex justify-content-between align-items-center py-2 px-3 text-muted bg-white shadow-sm"
    >
      <div class="d-flex align-items-center space-x-2">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="16"
          height="16"
          fill="currentColor"
          class="bi bi-map-fill"
          viewBox="0 0 16 16"
        >
          <path
            fill-rule="evenodd"
            d="M16 .5a.5.5 0 0 0-.598-.49L10.5.99 5.598.01a.5.5 0 0 0-.196 0l-5 1A.5.5 0 0 0 0 1.5v14a.5.5 0 0 0 .598.49l4.902-.98 4.902.98a.5.5 0 0 0 .196 0l5-1A.5.5 0 0 0 16 14.5zM5 14.09V1.11l.5-.1.5.1v12.98l-.402-.08a.5.5 0 0 0-.196 0zm5 .8V1.91l.402.08a.5.5 0 0 0 .196 0L11 1.91v12.98l-.5.1z"
          />
        </svg>
        <div class="lh-1">
          <span>Process Map</span>
        </div>
      </div>

      <div class="d-flex align-items-center"></div>
    </div>

    <div class="h-100">
      <ModelerApp id="modeler-app" @clickNode="clickNode"> </ModelerApp>
    </div>

    <div class="modal-footer d-flex justify-content-between align-items-center">
      <button type="button" class="btn bg-secondary text-white px-5">
        Back
      </button>

      <button type="button" class="btn bg-primary text-white px-5">Next</button>
    </div>
  </div>
</template>

<style>
.btn-outline-gray {
  border: 1px solid var(--borders, #cdddee) !important;
}
</style>
