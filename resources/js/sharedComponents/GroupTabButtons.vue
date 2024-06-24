<template>
  <div>
    <div class="sm:hidden">
      <select id="tabs" name="tabs" class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm">
        <option v-for="tab in tabsModel" :key="tab.name" :selected="tab.current">
          {{ tab.name }}
        </option>
      </select>
    </div>
    <div class="hidden sm:block">
      <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
          <span
            v-for="(tab, index) in tabsModel"
            :key="index"
            @click="onChange(tab, index)"
            :class="[tab.current ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700', 'whitespace-nowrap border-b-2 px-1 py-2 text-sm font-semibold']"
            :aria-current="tab.current ? 'page' : undefined">{{ tab.name }}</span>
        </nav>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, computed } from 'vue';

export default defineComponent({
  props: {
    tabs: {
      type: Array,
      default: () => [],
    },
  },
  emits: ['update:tabs'],
  setup(props, { emit }) {
    const tabsModel = computed({
      get() {
        return props.tabs;
      },
      set(value) {
        emit('update:tabs', value);
      },
    });

    const onChange = (item, index) => {
      item.onClick && item.onClick();

      tabsModel.value.forEach((e, i) => {
        e.current = false;

        index === i && (e.current = true);
      });
    };

    return { tabsModel, onChange };
  },
});
</script>
