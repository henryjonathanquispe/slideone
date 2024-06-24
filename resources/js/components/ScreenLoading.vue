<template>
  <div
    class="flex text-gray-500 justify-center text-center items-center bg-white w-100">
    <div class="flex flex-col items-center">
      <div class="bg-white dark:bg-slate-300 p-4 ring-1 ring-slate-700/5 rounded-lg shadow-lg max-w-xs h-28 w-80">
        <div class="flex space-x-4 animate-pulse">
          <div class="rounded-full bg-slate-200 dark:bg-slate-300 h-10 w-10" />
          <div class="flex-1 space-y-6 py-1">
            <div class="h-2 bg-slate-200 dark:bg-slate-300 rounded" />
            <div class="space-y-3">
              <div class="grid grid-cols-3 gap-4">
                <div class="h-2 bg-slate-200 dark:bg-slate-300 rounded col-span-2" />
                <div class="h-2 bg-slate-200 dark:bg-slate-300 rounded col-span-1" />
              </div>
              <div class="h-2 bg-slate-200 dark:bg-slate-300 rounded" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
});
</script>
