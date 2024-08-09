import { ref } from 'vue';

export const includeNodeTypes = ['task', 'startevent', 'exclusivegateway', 'inclusivegateway', 'parallelgateway', 'endevent', 'manualtask', 'scripttask', 'eventbasedgateway'];
export const initNodeTypes = ['task', 'startevent', 'exclusivegateway', 'inclusivegateway', 'parallelgateway', 'manualtask', 'scripttask', 'callactivity'];

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

  const resetScreenProperties = () => {
    node.value = null;
    screenSize.value = 'desktop';
    screen.value = null;
  };

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
    resetScreenProperties,
  };
};
