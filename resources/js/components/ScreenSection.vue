<script>
import { defineComponent, onMounted, ref } from "vue";
import { VueFormRenderer } from "vue-form-renderer";

export default defineComponent({
  emits: ["expand", "close"],
  props: ["useSlide"],
  components: {
    VueFormRenderer,
  },
  setup(props, { emit }) {
    const data = props.useSlide.data;

    const screen = ref({});

    const onExpand = () => {
      emit("expand");
    };

    const onClose = () => {
      emit("close");
    };

    onMounted(() => {
      window.ProcessMaker.apiClient.get(`screens/5`).then((response) => {
        screen.value = response.data;
      });
    });

    return {
      onClose,
      onExpand,
      data,
      screen,
    };
  },
});
</script>

<template>
  <div class="h-100">
    <div
      class="d-flex justify-content-between align-items-center py-2 px-3 text-muted bg-white shadow-sm"
    >
      <div class="d-flex align-items-center space-x-2">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="16"
          height="16"
          fill="currentColor"
          class="bi bi-eye-fill"
          viewBox="0 0 16 16"
        >
          <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
          <path
            d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"
          />
        </svg>
        <div class="lh-1">
          <span>Preview</span>
        </div>
      </div>

      <div class="d-flex align-items-center">
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
          <button
            @click="onExpand"
            type="button"
            class="btn d-flex align-items-center space-x-3 text-muted btn-outline-gray rounded-lg shadow-sm"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="16"
              height="16"
              fill="currentColor"
              class="bi bi-fullscreen"
              viewBox="0 0 16 16"
            >
              <path
                d="M1.5 1a.5.5 0 0 0-.5.5v4a.5.5 0 0 1-1 0v-4A1.5 1.5 0 0 1 1.5 0h4a.5.5 0 0 1 0 1zM10 .5a.5.5 0 0 1 .5-.5h4A1.5 1.5 0 0 1 16 1.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 0-.5-.5h-4a.5.5 0 0 1-.5-.5M.5 10a.5.5 0 0 1 .5.5v4a.5.5 0 0 0 .5.5h4a.5.5 0 0 1 0 1h-4A1.5 1.5 0 0 1 0 14.5v-4a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v4a1.5 1.5 0 0 1-1.5 1.5h-4a.5.5 0 0 1 0-1h4a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 1 .5-.5"
              />
            </svg>
            <span> Full Screen </span>
          </button>

          <button @click="onClose" type="button" class="btn">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="20"
              height="20"
              fill="currentColor"
              class="bi bi-x"
              viewBox="0 0 16 16"
            >
              <path
                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"
              />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <div
      class="h-100 d-flex flex-column align-items-center justify-content-center"
    >
      <pre>{{ data }}</pre>
      <vue-form-renderer
        v-model="previewData"
        :config="screen.config"
        :computed="screen.computed"
        :custom-css="screen.custom_css"
        :watchers="screen.watchers"
        :show-errors="true"
      />
    </div>
  </div>
</template>

<style>
.btn-outline-gray {
  border: 1px solid var(--borders, #cdddee) !important;
}
</style>
