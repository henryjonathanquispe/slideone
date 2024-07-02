import { ref } from 'vue';
import { run, completeTask, selectRoute } from '../utils/slideshowApi';

export default {};

export const useNavigation = () => {
  const history = ref([]);
  const currentElement = ref();
  const alternative = ref('A');

  const callTestEngine = async (initialNode, stopNode) => {
    // Call to the test engine to start the process at this point
    const response = await run({
      initialNode,
      alternative: alternative.value,
      stopNode,
    });

    return response;

    // currentElement.value = response.data.trace.find((element) => element.status === 'ACTIVE' || element.status === 'INCOMING');
  };

  const init = async (nodeId) => {
    const response = await callTestEngine(nodeId, nodeId, 'A');

    history.value = response.data.trace;

    currentElement.value = response.data.trace.find((e) => e.id === nodeId);

    return currentElement.value;
  };

  const complete = async (element) => {
    debugger;
    const response = await completeTask(element.run_id, element.token_id);

    console.log('BREAKPOINT::: COMPLETE', response);

    history.value = response.data.trace;
  };

  const gatewayRoute = async (element) => {
    debugger;

    const response = await selectRoute(element.run_id, element.id, element.nextFlows[0]);

    console.log('BREAKPOINT::: GATEWAY', response);
    if (response.data) {
      history.value = response.data.trace;
    }
  };

  const next = async () => {
    const currentIndex = history.value.findIndex((e) => e.id === currentElement.value.id);

    const nextNode = history.value.find((e, index) => index > currentIndex && ['task', 'startEvent', 'exclusiveGateway', 'inclusiveGateway'].includes(e.type));

    if (nextNode) {
      currentElement.value = nextNode;

      return nextNode;
    }

    if (currentElement.value.type === 'task') {
      await complete(currentElement.value);
    }
    if (currentElement.value.type === 'exclusiveGateway') {
      await gatewayRoute(currentElement.value);
    }

    debugger;

    const response = await next();

    return response;
  };

  const getRoutedFlows = (node) => node.routedFlows?.map((e) => e.id);

  const getCompletedNodes = () => {
    const currentIndex = history.value.findIndex((e) => e.id === currentElement.value.id);

    const res = history.value.slice(0, currentIndex);

    const completed = res.reduce((acc, value) => {
      const flows = getRoutedFlows(value);

      acc = acc.concat(value.id, flows);

      return acc;
    }, []);

    return completed;
  };

  const prev = () => {

  };

  return {
    history,
    currentElement,
    alternative,
    init,
    complete,
    gatewayRoute,
    next,
    prev,
    callTestEngine,
    getCompletedNodes,
  };
};

// Click in the first node to init the testing
// const initTesting = async (nodeId) => {
//   console.log('BREAKPOINT::: INIT TESTING');

//   modelerApp.value.colorizeElement(node.value, colors.ACTIVE);

//   const response = await callTestEngine(nodeId, nodeId, 'A');

//   currentElement.value = response;
// };
