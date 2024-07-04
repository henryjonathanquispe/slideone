import { ref, computed, nextTick } from 'vue';
import { run, completeTask, selectRoute } from './utils/slideshowApi';

export default {};

export const useNavigation = () => {
  const history = ref([]);
  const currentElement = ref();
  const alternative = ref('A');
  const gatewayFlow = ref();
  const gatewayOptions = ref({});

  const callTestEngine = async (initialNode, stopNode) => {
    // Call to the test engine to start the process at this point
    const response = await run({
      initialNode,
      alternative: alternative.value,
      stopNode,
    });

    return response;
  };

  // Init the testing from a specific node
  const init = async (nodeId) => {
    const response = await callTestEngine(nodeId, nodeId, 'A');

    history.value = response.data.trace;

    currentElement.value = response.data.trace.find((e) => e.id === nodeId);

    return currentElement.value;
  };

  /**
   * Complete the input element using the API Testing
   * @param {*} element
   */
  const complete = async (element) => {
    const response = await completeTask(element.run_id, element.token_id);

    history.value = response.data.trace;
  };

  /**
   * Select route in a specific gateway using the API Testing
   * @param {*} element
   */
  const gatewayRoute = async (element) => {
    const response = await selectRoute(element.run_id, element.id, gatewayFlow.value || element.nextFlows[0]);

    // Save the current gateway options
    if (!gatewayOptions[currentElement.value.id]) {
      gatewayOptions[currentElement.value.id] = currentElement.value.nextFlows;
    }

    if (response.data) {
      history.value = response.data.trace;
    }
  };

  const next = async () => {
    const currentIndex = history.value.findIndex((e) => e.id === currentElement.value.id);

    const nextNode = history.value.find((e, index) => index > currentIndex && ['task', 'startEvent', 'exclusiveGateway', 'inclusiveGateway', 'endEvent'].includes(e.type));

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

    const response = await next();

    return response;
  };

  const getRoutedFlows = (node) => node.routedFlows?.map((e) => e.id);

  /**
   * Return the nodes from initial to current element
   * INPUT: [node1, node2, node3, node4(current), node5, node6]
   * OUTPUT: [node1, node2, node3]
   * @returns Array
   */
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

  /**
   * Return all nodes from current element to final element
   * INPUT: [node1, node2, node3, node4(current), node5, node6]
   * OUTPUT: [node5, node6]
   * @returns Array
   */
  const getLastNodes = () => {
    const currentIndex = history.value.findIndex((e) => e.id === currentElement.value.id);

    const res = history.value.slice(currentIndex, history.value.length);

    const last = res.reduce((acc, value) => {
      const flows = getRoutedFlows(value);

      acc = acc.concat(value.id, flows);

      return acc;
    }, []);

    return last;
  };

  /**
   * Return all nodes
   * @returns Array
   */
  const getAllNodes = () => {
    const last = history.value.reduce((acc, value) => {
      const flows = getRoutedFlows(value);

      acc = acc.concat(value.id, flows);

      return acc;
    }, []);

    return last;
  };

  const prev = () => {
    const currentIndex = history.value.findIndex((e) => e.id === currentElement.value.id);

    const nextNode = history.value.findLast((e, index) => index < currentIndex && ['task', 'startEvent', 'exclusiveGateway', 'inclusiveGateway', 'endEvent'].includes(e.type));

    debugger;
    if (nextNode) {
      currentElement.value = nextNode;

      if (nextNode.type.includes('Gateway')) {
        // Delete nodes after current element
        nextTick(() => {
          nextTick(() => {
            history.value.splice(currentIndex, history.value.length - 1);
          });
        });
      }

      return nextNode;
    }

    return null;
  };

  const getCurrentIndex = () => history.value.findIndex((e) => e.id === currentElement.value.id);

  const hasPrevNode = computed(() => getCurrentIndex() > 0);

  const hasNextNode = computed(() => getCurrentIndex() !== history.value.length - 1 || history.value[getCurrentIndex()].type !== 'endEvent');

  const reset = () => {
    currentElement.value = null;
    history.value = [];
    alternative.value = 'A';
    gatewayFlow.value = null;
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
    getLastNodes,
    getCurrentIndex,
    getAllNodes,
    hasPrevNode,
    hasNextNode,
    reset,
    gatewayFlow,
    gatewayOptions,
  };
};
