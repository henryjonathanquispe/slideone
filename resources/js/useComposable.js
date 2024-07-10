import { ref } from 'vue';

export const includeNodeTypes = ['task', 'startevent', 'exclusivegateway', 'inclusivegateway', 'parallelgateway', 'endevent', 'manualtask', 'scripttask', 'eventbasedgateway'];
export const initNodeTypes = ['task', 'startevent', 'exclusivegateway', 'inclusivegateway', 'parallelgateway', 'manualtask', 'scripttask'];

export default ({
  appContainer, screenContainer, modelerContainer, slideAlert,
}) => {
  const node = ref();
  const screenSize = ref('desktop');
  const screen = ref();
  const panelConfig = ref('left-panel');
  const alternative = ref('A');

  const alert = (message, type) => {
    slideAlert.value.show(message, type);
  };

  const findMapElement = (id) => modelerContainer.value.modeler.definitions.rootElements[0].flowElements.find((e) => e.id == id);

  return {
    node,
    screenSize,
    appContainer,
    screenContainer,
    modelerContainer,
    slideAlert,
    panelConfig,
    screen,
    alternative,
    findMapElement,
    alert,
  };
};
