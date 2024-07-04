import { ref, computed, nextTick } from 'vue';
import {
  run, completeTask, selectRoute, goBack,
} from './utils/slideshowApi';

export default {};

export const useNavigation = () => {
  const history = ref([]);
  const currentElement = ref();
  const alternative = ref('A');
  const gatewayFlow = ref();
  const gatewayOptions = ref({});
  const lastNodesAfterBack = ref([]);

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

  const next = async () => {
    const currentIndex = history.value.findIndex((e) => e.id === currentElement.value.id);

    const nextNode = history.value.find((e, index) => index > currentIndex && ['task', 'startEvent', 'exclusiveGateway', 'inclusiveGateway', 'endEvent'].includes(e.type));

    if (nextNode) {
      currentElement.value = nextNode;

      // Save the current gateway options
      if (nextNode.type.includes('Gateway')) {
        if (!gatewayOptions[currentElement.value.id]) {
          gatewayOptions[currentElement.value.id] = currentElement.value.nextFlows;
        }
      }

      return nextNode;
    }

    if (currentElement.value.type === 'task') {
      const response = await completeTask(currentElement.value.run_id, currentElement.value.token_id);

      history.value = response.data.trace;
    }
    if (currentElement.value.type === 'exclusiveGateway') {
      const response = await selectRoute(currentElement.value.run_id, currentElement.value.id, gatewayFlow.value || currentElement.value.nextFlows[0]);

      if (response.data) {
        history.value = response.data.trace;
      }
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
  const getLastNodes = (element) => {
    const currentIndex = history.value.findIndex((e) => e.id === (element?.id || currentElement.value.id));

    const res = history.value.slice(currentIndex, history.value.length);

    const last = res.reduce((acc, value) => {
      const flows = getRoutedFlows(value);

      acc = acc.concat(value.id, flows);

      return acc;
    }, []);

    return last;
  };

  /**
   * Return all nodes from current element after execute goBack
   * @returns Array
   */
  const getLastNodesAfterBack = () => lastNodesAfterBack.value;

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

  const prev = async () => {
    // Get the prev Node using the API
    const response = await goBack(currentElement.value.run_id);

    if (response.data) {
      const prevNode = response.data.at(-1);

      // Save the last nodes for change the color to NORMAL
      lastNodesAfterBack.value = getLastNodes(prevNode);

      history.value = response.data;

      currentElement.value = prevNode;

      return prevNode;
    }

    return null;
  };

  const getCurrentIndex = () => history.value.findIndex((e) => e.id === currentElement.value.id);

  const getCurrentGatewayOptions = () => gatewayOptions[currentElement.value.id] || [];

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
    next,
    prev,
    callTestEngine,
    getCompletedNodes,
    getLastNodes,
    getCurrentIndex,
    getAllNodes,
    getCurrentGatewayOptions,
    getLastNodesAfterBack,
    hasPrevNode,
    hasNextNode,
    reset,
    gatewayFlow,
    gatewayOptions,
  };
};
