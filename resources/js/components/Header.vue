<template>
  <div
    class="d-flex justify-content-between align-items-center py-2 px-4 text-white bg-dark shadow-sm"
  >
    <div class="d-flex align-items-center space-x-3">
      <img
        class="me-3"
        src="https://jonathanquispe.developer.processmaker.net/img/processmaker_icon.png?id=8122489cabc54a228d6b8e8efb72dece"
        alt=""
        width="32"
        height="32"
      />

      <button
        type="button"
        class="btn bg-dropdown btn-sm dropdown-toggle"
        data-toggle="dropdown"
        aria-expanded="false"
      >
        Option
      </button>
      <div class="dropdown-menu">
        <a class="dropdown-item" href="#">Alt A</a>
        <a class="dropdown-item" href="#">Alt B</a>
      </div>
    </div>

    <div class="d-flex">
      <div class="d-flex space-x-3 justify-content-sm-center">
        <button
          @click="openModal"
          type="button" class="btn btn-primary btn gap-3">
          SHARE SLIDESHOW
        </button>

        <button
          type="button"
          class="btn bg-dark text-white btn-sm dropdown-toggle d-flex align-items-center space-x-2"
          data-toggle="dropdown"
          aria-expanded="false"
        >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="16"
              height="16"
              fill="currentColor"
              class="bi bi-gear-fill"
              viewBox="0 0 16 16"
            >
              <path
                d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"
              />
            </svg>
            <span>
            Options
            </span>
         
        </button>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="#">Alt C</a>
          <a class="dropdown-item" href="#">Alt D</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  emits: ["open-modal"],
  setup(props, { emit }) {
    const openModal = () => {
      emit("open-modal");
    };

    return {
      openModal
    };
  },
});
</script>

<style scoped>
.bg-dropdown {
  background: #f8e9c3;
}

.space-x-2>:not([hidden])~:not([hidden]) {
    --tw-space-x-reverse:0 ;
    margin-right: calc(0.5rem * var(--tw-space-x-reverse));
    margin-left: calc(0.5rem * calc(0.5 - var(--tw-space-x-reverse)));
}

</style>
