<template>
  <div class="mt-2 flex rounded-md shadow-sm">
    <div
      ref="chipContainer"
      @click="focusInput"
      class="flex flex-grow items-stretch focus-within:z-10 border-1 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 overflow-hidden">
      <div
        class="flex m-1 px-1 border border-gray-200 items-center space-x-2"
        v-for="(chip, i) of chips"
        :key="chip.label">
        <span class="text-nowrap">{{ chip }}</span>
        <span
          class="flex items-center"
          @click="deleteChip(i)"><IClose class="h-3 w-3 hover:cursor-pointer" /></span>
      </div>

      <input
        ref="input"
        class="flex border-none bg-transparent px-2 shadow-input-none"
        v-model="currentInput"
        @keypress.enter="saveChip"
        @keydown.delete="backspaceDelete">
    </div>

    <button
      @click="onClick"
      :disabled="props.disableButton"
      type="button"
      :class="['text-nowrap text-white -ml-px gap-x-1.5 rounded-r-sm px-3 py-2 text-sm font-semibold ring-1 ring-inset ring-gray-300' ,{
        'bg-secondary':!props.disableButton,
        'bg-gray-300':props.disableButton,
        'hover:opacity-75': true,
        'hover:cursor-default': props.disableButton
      }]">
      {{ $t(props.labelButton) }}
    </button>
  </div>
</template>

<script>
import { defineComponent, ref, computed } from 'vue';
import { IClose } from '../icons';

export default defineComponent({
  components: {
    IClose,
  },
  props: {
    set: {
      type: Boolean,
      default: true,
    },
    data: {
      type: Array,
      default: () => [],
    },
    labelButton: {
      type: String,
      default: () => 'default',
    },
    disableButton: {
      type: Boolean,
      default: false,
    },
  },
  emit: ['update:data', 'onClick'],
  setup(props, { emit }) {
    const chips = computed({
      get() {
        return props.data;
      },
      set(value) {
        emit('update:data', value);
      },
    });

    const currentInput = ref('');

    const chipContainer = ref();

    const input = ref();

    const updateScroll = () => {
      chipContainer.value.scrollLeft = chipContainer.value.scrollWidth;
    };

    const focusInput = () => {
      input.value.focus();
    };

    const saveChip = () => {
      if ((props.set && chips.value.indexOf(currentInput) === -1) || !props.set) {
        chips.value.push(currentInput.value);
      }

      currentInput.value = '';

      updateScroll();

      input.value.focus();
    };

    const deleteChip = (index) => {
      chips.value.splice(index, 1);

      updateScroll();

      input.value.focus();
    };

    const backspaceDelete = ({ which }) => {
      if (which === 8 && currentInput.value === '') {
        chips.value.splice(chips.value.length - 1);
      }
    };

    const onClick = () => {
      emit('onClick');
    };

    return {
      chips,
      currentInput,
      chipContainer,
      input,
      saveChip,
      deleteChip,
      backspaceDelete,
      focusInput,
      props,
      onClick,
    };
  },
});
</script>

<style scoped>
.shadow-input-none:focus{
  box-shadow: none !important;
}
</style>
