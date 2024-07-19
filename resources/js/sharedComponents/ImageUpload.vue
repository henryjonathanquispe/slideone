<template>
  <div class="relative">
    <div
      v-if="!data.length"
      @drop.prevent="handleDrop"
      @dragenter.prevent="handleDragEnter"
      @dragover.prevent="handleDragOver"
      @dragleave.prevent="handleDragLeave"
      :class="['flex justify-center py-2 rounded-sm border-dashed !border bg-gray-100 border-gray-500',{
        'opacity-20':isDragging || disabledField
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
      :class="{
        'opacity-20':disabledField
      }"
      class="p-2 transition-all border-x border-y border-gray-200 rounded-md">
      <li
        v-for="(image, index) in data"
        :key="index"
        class="flex justify-between items-center space-x-2">
        <div class="flex min-w-0 space-x-2">
          <div class="h-12 w-12 flex items-center shadow-lg bg-gray-200">
            <img class="flex-none bg-gray-50" :src="image.file || image.src" alt="">
          </div>

          <div class="flex flex-col text-xs min-w-0 justify-center items-start flex-auto">
            <p class="text-gray-500 font-semibold truncate">
              {{ image.name }}
            </p>

            <p v-if="image?.size" class="text-gray-500 truncate">
              {{ image.size ? formatBytes(image.size): "" }} - {{ $t("Uploaded") }}
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

    <div
      v-show="disabledField"
      class="flex absolute inset-0 h-full w-full justify-center items-center">
      <Spinner class="animate-spin h-5 w-5 items-center justify-center text-gray-400" />
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, computed } from 'vue';
import { IPlus, IClose } from '../icons';
import Spinner from './Spinner.vue';

const formatBytes = (bytes) => {
  if (bytes === 0) return '0 Bytes';
  const k = 1000;
  const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  const formattedNumber = parseFloat((bytes / k ** i).toFixed(2));
  return `${formattedNumber} ${sizes[i]}`;
};

export default defineComponent({
  components: {
    IPlus,
    IClose,
    Spinner,
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
    maxSize: {
      type: Number,
      default: () => 50000000,
    },
  },
  emit: ['update:value', 'remove', 'add', 'max-size-error'],
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
              size: elFile.size,
              type: elFile.type,
            };

            if (elFile.size < props.maxSize) {
              data.value.push(element);

              emit('add', element);
              return;
            }

            emit('error', { maxSizeError: true });
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
      formatBytes,
    };
  },
});
</script>
