import { ref } from 'vue';

export default ({ appContainer, screenContainer, modelerContainer }) => {
  const node = ref();
  const screenSize = ref('desktop');
  const screen = ref();
  const panelConfig = ref('left-panel');

  return {
    node,
    screenSize,
    appContainer,
    screenContainer,
    modelerContainer,
    panelConfig,
    screen,
  };
};
