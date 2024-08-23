<template>
  <div ref="modelerContainer" class="flex flex-col justify-between border w-full h-full max-h-full">
    <div
      class="
        flex flex-row justify-between align-items-center py-2 px-3 text-muted bg-white shadow-sm
      ">
      <div class="flex basis-11/12 align-items-center space-x-2 w-fit">
        <div class="lh-1 line-clamp-1">
          <span> {{ $t(modelerGlobal?.process?.name) }}</span>
        </div>
      </div>
      {{ startEvents }}
      <div class="flex basis-1/12 items-center text-xs font-semibold">
        {{ $t('Powered by ProcessMaker') }}
      </div>
    </div>

    <div class="h-100">
      <b-container
        fluid
        ref="container"
        id="modeler-app"
        class="h-100 position-relative p-0">
        <b-card no-body class="h-100">
          <b-card-body class="overflow-hidden position-relative p-0 h-100">
            <modeler
              :key="count"
              ref="modeler"
              :show-toolbar="false"
              :read-only="true"
              @set-xml-manager="xmlManager = $event"
              @validate="validationErrors = $event"
              @warnings="warnings = $event"
              :decorations="decorations"
              @click="clickNode" />
          </b-card-body>
        </b-card>
      </b-container>
    </div>

    <Teleport to=".rail-container">
      <ButtonsNavigation
        class="justify-end"
        :show-back-button="hasPrevNode"
        :show-next-button="hasNextNode"
        :show-start-button="showStartButton"
        :start-button-options="startButtonOptions"
        @prev="onPrev"
        @start="onStart"
        @next="onNext" />
    </Teleport>
  </div>
</template>

<script>
import {
  defineComponent, ref, watch, nextTick, onUnmounted, onMounted, computed,
} from 'vue';
import { Modeler } from '@processmaker/modeler';
import { t } from 'i18n';
import Teleport from '../../sharedComponents/Teleport.vue';
import ButtonsNavigation from '../ButtonsNavigation.vue';
import { initNodeTypes } from '../../useComposable';
import { modeler as modelerGlobal } from '../../utils/slideshowApi';

const nonSelectedOpacity = 0.6;
const nonPassedWidth = 4;

const colors = {
  NORMAL: {
    highlighter: {
      name: 'stroke',
      options: {
        padding: 10,
        attrs: {},
      },
    },
  },
  COMPLETED: {
    highlighter: {
      name: 'stroke',
      options: {
        padding: 10,
        attrs: {
          stroke: '#00BF9C',
          'stroke-width': nonPassedWidth,
          opacity: nonSelectedOpacity,
        },
      },
    },
  },
  ERROR: {
    highlighter: {
      name: 'stroke',
      options: {
        padding: 10,
        attrs: {
          stroke: '#ED4757',
          'stroke-width': nonPassedWidth,
          opacity: nonSelectedOpacity,
        },
      },
    },
  },
  LOOP: {
    highlighter: {
      name: 'stroke',
      options: {
        padding: 10,
        attrs: {
          stroke: '#17A2B8',
          'stroke-width': nonPassedWidth,
          opacity: nonSelectedOpacity,
        },
      },
    },
  },
  ACTIVE: {
    highlighter: {
      name: 'stroke',
      options: {
        padding: 10,
        attrs: {
          stroke: '#15A6FB',
          'stroke-width': nonPassedWidth,
          opacity: nonSelectedOpacity,
        },
      },
    },
  },
  ROUTED: {
    highlighter: {
      name: 'stroke',
      options: {
        padding: 10,
        rx: 5,
        ry: 5,
        attrs: {
          'stroke-width': 5,
          stroke: '#00BF9C',
          opacity: nonSelectedOpacity,
        },
      },
    },
  },
  ROUTE_ERROR: {
    highlighter: {
      name: 'stroke',
      options: {
        padding: 10,
        rx: 5,
        ry: 5,
        attrs: {
          'stroke-width': 5,
          stroke: '#ED4757',
          opacity: nonSelectedOpacity,
        },
      },
    },
  },
};

export default defineComponent({
  emits: ['expand', 'close', 'clickNode', 'next', 'prev'],
  props: ['useSlideShow', 'useNavigation'],
  components: {
    Modeler,
    Teleport,
    ButtonsNavigation,
  },
  setup(props, { emit, expose }) {
    const validationErrors = ref();
    const xmlManager = ref();
    const modeler = ref();
    const modelerContainer = ref();
    const container = ref();
    const resizer = ref();
    const count = ref(0);
    const resizeObserver = ref();
    const showStartButton = ref(true);
    const decorations = ref({
      borderOutline: {},
    });
    const {
      node, panelConfig, alternative, alert,
    } = props.useSlideShow;
    const {
      init, next, getCompletedNodes, hasPrevNode, hasNextNode, reset, getAllNodes, getFirstNode,
    } = props.useNavigation;

    const startButtonOptions = computed(() => {
      if (!modeler.value?.definitions?.rootElements) {
        return [];
      }

      return modeler.value.definitions.rootElements[0].flowElements.filter((e) => e.$type.includes('Start')).map((e) => ({ value: e.id, label: e.name }));
    });

    // Set colors an array of nodes
    const colorizeElement = (nodeArray, color) => {
      const newElements = Array.isArray(nodeArray) ? nodeArray : [nodeArray];

      newElements.forEach((nodeValue) => {
        modeler.value.$parent.$set(
          modeler.value.$parent.decorations.borderOutline,
          nodeValue,
          color,
        );
      });
    };

    // Clear styles of all nodes
    const clearColorize = () => {
      modeler.value.$parent.$set(
        modeler.value.$parent.decorations,
        'borderOutline',
        {},
      );
    };

    const onExpand = () => {
      emit('expand');
    };

    const onClose = () => {
      emit('close');
    };

    // Find xml element in modeler
    const findMapElement = (id) => modeler.value.definitions.rootElements[0].flowElements.find((e) => e.id == id);

    // OnClik Next Button, uses the composable and update the modeler nodes styles
    const onNext = async () => {
      try {
        const nextNode = await next();

        if (nextNode) {
          const completedNodes = getCompletedNodes();

          colorizeElement(completedNodes, colors.COMPLETED);
          colorizeElement(nextNode.id, colors.ACTIVE);

          node.value = findMapElement(nextNode.id);
        }
      } catch (e) {
        alert(e.message, 'danger');
        console.error(e);
      }
    };

    // Click in new node, reset the modeler styles & composable
    const clickNode = async (e) => {
      modeler.value.clearSelection(); // Reset the current selection in modeler

      if (!initNodeTypes.find((el) => e.node.$type.toLowerCase().includes(el))) {
        alert(t('Unselectable'), 'danger');

        return;
      }

      showStartButton.value = false;

      // Clean all elements
      if (node.value) {
        const allNodes = getAllNodes();

        colorizeElement(allNodes, colors.NORMAL);

        reset();
      }

      try {
        const element = await init(e.node.id, alternative.value);

        colorizeElement(element.id, colors.ACTIVE);

        // Show two panels
        panelConfig.value = 'two-panels';
        nextTick(() => {
          node.value = e.node;
        });
      } catch (error) {
        console.error(error);
        alert(error.message, 'danger');
      }
    };

    const onPrev = async () => {
      // Get first selected node
      const firstNode = getFirstNode();

      const nodeResult = findMapElement(firstNode.id);

      clickNode({ node: nodeResult });
    };

    const onStart = async (startEvent) => {
      const nodeResult = findMapElement(startEvent.value);

      clickNode({ node: nodeResult });
    };

    // Set the new alternative and
    const unwatchAlternative = watch(() => alternative.value, () => {
      // Reset navigation & modeler
      showStartButton.value = true;
      reset();
      modeler.value.reset({ readOnly: true });

      nextTick(() => {
        modeler.value.loadXML(alternative.value === 'A' ? window.ProcessMaker.modeler.xml : window.ProcessMaker.modeler.xmlB);
      });
    });

    onMounted(() => {
      // IMPORTANT: This watcher should fix the issue related to resizing the slideshow in full screen mode
      resizeObserver.value = new ResizeObserver(modeler.value.handleResize);
      resizeObserver.value.observe(modelerContainer.value);
    });

    onUnmounted(() => {
      unwatchAlternative();
      resizeObserver.value.unobserve(modelerContainer.value);
    });

    expose(['onPrev', 'onNext', 'modeler']);

    return {
      alternative,
      count,
      clearColorize,
      container,
      clickNode,
      decorations,
      hasPrevNode,
      hasNextNode,
      colorizeElement,
      modeler,
      modelerContainer,
      modelerGlobal,
      onStart,
      onClose,
      onExpand,
      onPrev,
      onNext,
      resizer,
      startButtonOptions,
      showStartButton,
      validationErrors,
      xmlManager,
    };
  },
});
</script>

<style>
.btn-outline-gray {
  border: 1px solid var(--borders, #cdddee) !important;
}
</style>
