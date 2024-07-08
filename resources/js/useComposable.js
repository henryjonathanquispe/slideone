import { ref } from 'vue';

export default ({ appContainer, screenContainer, modelerContainer }) => {
  const node = ref();
  const screenSize = ref('desktop');
  const screen = ref();
  const panelConfig = ref('left-panel');
  const alternative = ref('A');

  const findMapElement = (id) => modelerContainer.value.modeler.definitions.rootElements[0].flowElements.find((e) => e.id == id);

  return {
    node,
    screenSize,
    appContainer,
    screenContainer,
    modelerContainer,
    panelConfig,
    screen,
    alternative,
    findMapElement,
  };
};
