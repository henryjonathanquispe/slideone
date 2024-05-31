import { ref, onMounted, computed, onUnmounted } from "vue";

export const useSlideShow = () => {
  const data = ref("DEFAULT");

  return { data };
};
