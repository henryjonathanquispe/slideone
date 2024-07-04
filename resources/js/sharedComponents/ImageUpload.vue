<template>
  <div
    :class="{ 'opacity-50':disabledField }">
    <div
      v-if="!data.length"
      @drop.prevent="handleDrop"
      @dragenter.prevent="handleDragEnter"
      @dragover.prevent="handleDragOver"
      @dragleave.prevent="handleDragLeave"
      :class="['flex justify-center py-2 rounded-sm border-dashed !border bg-gray-100 border-gray-500',{
        'opacity-50':isDragging
      }]">
      <slot
        name="placeholder"
        v-bind="{
          multiple,
          onFileChange
        }">
        <label
          for="file-upload"
          class="flex items-center mb-0 text-sm text-gray-600 cursor-pointer space-x-1">
          <IPlus />

          <span class="font-semibold">{{ $t("Drag or click here to upload") }} </span>

          <input
            :multiple="props.multiple"
            id="file-upload"
            name="file-upload"
            type="file"
            @change="onFileChange"
            class="sr-only">
        </label>
      </slot>
    </div>

    <ul
      v-if="data.length"
      role="list"
      class="p-2 transition-all">
      <li
        v-for="(image, index) in data"
        :key="index"
        class="flex justify-between items-center space-x-2">
        <div class="flex min-w-0 space-x-2">
          <div class="h-12 w-12 flex items-center shadow-lg bg-gray-200">
            <img class="flex-none bg-gray-50" :src="image.file || image.src" alt="">
          </div>

          <div class="flex min-w-0 items-center flex-auto">
            <p class="text-xs text-gray-500 truncate">
              {{ image.name }}
            </p>
          </div>
        </div>

        <div
          @click="removeImage(index)"
          :class="{ 'opacity-50': disabledField }"
          class="shrink-0 flex flex-col items-end text-gray-600">
          <IClose class="h-4 w-4 hover:cursor-pointer" />
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
import { defineComponent, ref, computed } from 'vue';
import { IPlus, IClose } from '../icons';

export default defineComponent({
  components: {
    IPlus,
    IClose,
  },
  props: {
    multiple: Boolean,
    disabled: {
      type: Boolean,
      default: () => false,
    },
    value: {
      type: Array,
      default: () => [],
    },
  },
  emit: ['update:value', 'remove', 'add'],
  setup(props, { emit }) {
    const data = computed({
      get() {
        return props.value;
      },
      set(value) {
        emit('update:value', value);
      },
    });

    const disabledField = computed(() => props.disabled);

    const isDragging = ref(false);

    const fileReader = async (file, callback) => {
      const reader = new FileReader();

      reader.onload = callback;

      reader.readAsDataURL(file);
    };

    const processFiles = (files) => {
      const arrayFiles = files;

      Array.from(arrayFiles).some((elFile) => {
        if (elFile.type.startsWith('image/')) {
          fileReader(elFile, (e) => {
            const element = {
              file: e.target.result,
              name: elFile.name,
            };

            data.value.push(element);

            emit('add', element);
          });
        }

        return !props.multiple;
      });
    };

    const onFileChange = (event) => {
      processFiles(event.target.files);
      isDragging.value = false;
    };

    const handleDragEnter = () => isDragging.value = true;

    const handleDragOver = () => isDragging.value = true;

    const handleDragLeave = () => isDragging.value = false;

    const removeImage = (index) => {
      if (disabledField.value) {
        return;
      }

      const el = data.value.splice(index, 1);
      emit('remove', index, el[0]);
    };

    const handleDrop = (ev) => {
      processFiles(ev.dataTransfer.files);
      isDragging.value = false;
    };

    return {
      data,
      props,
      isDragging,
      onFileChange,
      removeImage,
      handleDrop,
      handleDragEnter,
      handleDragOver,
      handleDragLeave,
      disabledField,
    };
  },
});
</script>
