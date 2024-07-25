import { ref, computed } from 'vue';
import { run, completeTask, selectRoute } from './utils/slideshowApi';
import { includeNodeTypes } from './useComposable';

export default {};

const uniqueArray = (array) => [...new Map(array.map((item) => [item.id, item])).values()];

/**
 * COMPOSABLE
 * Manage the navigation state in slideShow
 * @returns
 */
export const useNavigation = () => {
  const history = ref([]);
  const currentElement = ref(); // Current element in historical navigation
  const gatewayFlow = ref(); // This is the current gateway flow selected from the dropdown menu
  // This var contains all the gateway options to populate the dropdown menu on the related gateway screen
  const gatewayOptions = ref({});
  const lastNodesAfterBack = ref([]);

  const callTestEngine = async (initialNode, stopNode, alternative) => {
    // Call to the test engine to start the process at this point
    const response = await run({
      initialNode,
      alternative,
      stopNode,
    });

    return response;
  };

  // Init the testing from a specific node
  const init = async (nodeId, alternative) => {
    const response = await callTestEngine(nodeId, nodeId, alternative);

    history.value = response.data.trace;

    currentElement.value = response.data.trace.find((e) => e.id === nodeId);

    if (currentElement.value.type.includes('Gateway')) {
      if (!gatewayOptions.value[currentElement.value.id]) {
        gatewayOptions.value[currentElement.value.id] = currentElement.value.nextFlows;
      }
    }

    return currentElement.value;
  };

  // Next node in navigation, this function uses the endpoint to complete or select route
  const next = async () => {
    const currentIndex = history.value.findLastIndex((e) => e.id === currentElement.value.id);

    const nextNode = history.value.find((e, index) => index > currentIndex && includeNodeTypes.find((el) => e.type.toLowerCase().includes(el)));

    if (nextNode) {
      currentElement.value = nextNode;

      // Save the current gateway options
      if (nextNode.type.includes('Gateway')) {
        if (!gatewayOptions.value[currentElement.value.id]) {
          gatewayOptions.value[currentElement.value.id] = currentElement.value.nextFlows;
        }
      }

      return nextNode;
    }

    // COMPLETE THE ROUTE OR SELECT ROUTE DEPENDS CURRENT ELEMENT
    const { run_id, token_id, id } = currentElement.value;

    if (currentElement.value.type.toLowerCase().includes('task')) {
      const res = await completeTask(run_id, token_id);

      if (_.isEqual(res.data.trace, history.value)) {
        throw new Error('Trace data error, verify the Testing package Endpoint');
      }

      if (res.data) {
        history.value = res.data.trace;
      }
    }

    if (currentElement.value.type.includes('Gateway')) {
      const res = await selectRoute(
        run_id,
        id,
        gatewayFlow.value || currentElement.value.nextFlows[0],
      );

      if (_.isEqual(res.data.trace, history.value)) {
        throw new Error('Trace data error, verify the Testing package Endpoint');
      }

      if (res.data) {
        history.value = res.data.trace;
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
    const currentIndex = history.value.findLastIndex((e) => e.id === currentElement.value.id);

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
    const currentIndex = history.value.findLastIndex((e) => e.id === (element?.id || currentElement.value.id));

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

  const getCurrentIndex = () => history.value.findLastIndex((e) => e.id === currentElement.value.id);

  const getCurrentGatewayOptions = () => gatewayOptions.value[currentElement.value.id] || [];

  const hasPrevNode = computed(() => getCurrentIndex() > 0);

  const hasNextNode = computed(() => {
    if (!history.value.length) {
      return false;
    }

    return getCurrentIndex() !== history.value.length - 1 || history.value[getCurrentIndex()].type !== 'endEvent';
  });

  const reset = () => {
    currentElement.value = null; // Reset current element in the navigation
    history.value = []; // Reset the navigation history
    gatewayFlow.value = null; // Reset the current gatewayFlow
    gatewayOptions.value = {}; // Reset option flows on all process gateways
  };

  const getFirstNode = () => {
    const firstNode = history.value.at(0);

    return firstNode;
  };

  return {
    history,
    currentElement,
    init,
    next,
    callTestEngine,
    getCompletedNodes,
    getLastNodes,
    getCurrentIndex,
    getAllNodes,
    getFirstNode,
    getCurrentGatewayOptions,
    getLastNodesAfterBack,
    reset,
    hasPrevNode,
    hasNextNode,
    gatewayFlow,
    gatewayOptions,
  };
};
