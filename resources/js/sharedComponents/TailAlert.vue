<template>
  <div class="">
    <transition name="fade">
      <div
        v-if="visible"
        class="rounded-md p-3 alert w-96 shadow-2xl"
        :class="{
          'bg-green-50':type == 'success',
          'bg-red-50':type == 'danger',
        }">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg
              class="h-5 w-5"
              :class="{
                'text-green-400':type == 'success',
                'text-red-400':type == 'danger'
              }"
              viewBox="0 0 20 20"
              fill="currentColor"
              aria-hidden="true">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="ml-3">
            <p
              class="text-sm font-medium"
              :class="{
                'text-green-800':type == 'success',
                'text-red-800':type == 'danger'
              }">
              {{ message }}
            </p>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue';

export default defineComponent({
  props: {
    duration: {
      type: Number,
      default: 3000,
    },
  },
  setup(props, { expose }) {
    const visible = ref(false);
    const message = ref('');
    const type = ref('danger');

    const show = (msg, typeValue) => {
      message.value = msg;
      type.value = typeValue;

      visible.value = true;
      setTimeout(() => {
        visible.value = false;
      }, props.duration);
    };

    const close = () => {
      visible.value = false;
    };

    expose(['show']);

    return {
      visible,
      show,
      close,
      type,
      message,
    };
  },
});
</script>

<style scoped>
.alert {
  position: fixed;
  top: 20px;
  left: 50%;
  transition: all 0.3s ease;
  transform: translateX(-50%);
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>
