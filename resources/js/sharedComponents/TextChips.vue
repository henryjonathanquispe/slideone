<template>
  <div>
    <div class="mt-2 flex rounded-md shadow-sm">
      <div
        ref="chipContainer"
        @click="focusInput"
        class="flex flex-grow items-stretch focus-within:z-10 border-1 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 overflow-hidden">
        <div
          class="flex m-1 px-1 border border-gray-200 items-center space-x-2"
          v-for="(chip, i) of chips"
          :key="chip.label">
          <span class="text-nowrap text-sm font-normal tracking-tight text-[#556271]">
            {{ chip }}
          </span>
          <span
            class="flex items-center"
            @click="deleteChip(i)"><i class="fp-close h-3 w-3 hover:cursor-pointer" /></span>
        </div>

        <input
          ref="input"
          :placeholder="placeholder"
          class="flex border-none bg-transparent px-2 shadow-input-none placeholder:text-gray-400"
          v-model="currentInput"
          @keypress.enter="saveChip"
          @keydown.delete="backspaceDelete">
      </div>

      <button
        @click="onClick"
        :disabled="props.disableButton || loading"
        type="button"
        class="flex justify-center items-center text-nowrap
         text-white -ml-px gap-x-1.5 rounded-r-sm
          px-3 py-2 text-sm font-semibold ring-1 ring-inset ring-gray-300 w-28"
        :class="[{
          'bg-gray-600':!props.disableButton && !loading,
          'bg-gray-300':props.disableButton || loading,
          'hover:opacity-75': true,
          'hover:cursor-default': props.disableButton || loading
        }]">
        <Spinner v-if="loading" class="animate-spin h-5 w-5 text-white" />

        <span v-else>{{ $t(props.labelButton) }}</span>
      </button>
    </div>

    <p v-if="errors.length" class="mt-2 text-sm text-rose-400 space-x-4">
      <span v-for="(error, index) in errors" :key="index">{{ error }}</span>
    </p>
  </div>
</template>

<script>
import { defineComponent, ref, computed } from 'vue';
import Spinner from './Spinner.vue';

export default defineComponent({
  components: {
    Spinner,
  },
  props: {
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
    regexp: {
      type: Array,
      default: () => [],
    },
    clickButton: {
      type: Function,
      default: () => null,
    },
    placeholder: {
      type: String,
      default: () => '',
    },
  },
  emit: ['update:data'],
  setup(props, { emit }) {
    const errors = ref([]);
    const loading = ref(false);

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

    const validateRegexp = (value) => {
      if (props.regexp.length) {
        const result = props.regexp.filter((e) => {
          const exp = new RegExp(e.regexp, 'i');

          return !exp.test(value);
        });

        return props.regexp.length > result.length ? [] : result;
      }

      return [];
    };

    const saveChip = () => {
      errors.value = [];

      const validation = validateRegexp(currentInput.value);

      if (validation.length > 0) {
        errors.value = validation.map((e) => e.message);
        return;
      }

      if (chips.value.indexOf(currentInput) === -1) {
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

    const onClick = async () => {
      loading.value = true;

      if (props.clickButton) {
        await props.clickButton();
      }

      loading.value = false;
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
      errors,
      loading,
    };
  },
});
</script>

<style scoped>
.shadow-input-none:focus{
  box-shadow: none !important;
}
</style>
