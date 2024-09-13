import { ref } from 'vue';

export const includeNodeTypes = ['task', 'startevent', 'exclusivegateway', 'inclusivegateway', 'parallelgateway', 'endevent', 'manualtask', 'scripttask', 'servicetask', 'eventbasedgateway', 'callactivity', 'intermediate'];
export const initNodeTypes = ['task', 'startevent', 'exclusivegateway', 'inclusivegateway', 'parallelgateway', 'manualtask', 'scripttask', 'callactivity'];
// Nodes to complete with testing using the Complete ENDPOINT
export const completeNodeTypes = ['task', 'startevent', 'endevent', 'manualtask', 'scripttask', 'servicetask', 'eventbasedgateway', 'callactivity', 'intermediatecatchevent', 'intermediatethrowevent'];

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

  /**
   * Reset the current screen Form displayed in screenSection
   */
  const resetScreenForm = () => {
    screen.value = null;
  };

  /**
   * Reset the screen size to desktop
   */
  const resetScreenSize = () => {
    screenSize.value = 'desktop';
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
    resetScreenForm,
    resetScreenSize
  };
};
