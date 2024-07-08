<template>
  <div class="flex flex-col justify-between border w-100 h-100 max-h-full">
    <div
      class="flex justify-between align-items-center py-2 px-3 text-muted bg-white shadow-sm">
      <div class="flex align-items-center space-x-2">
        <IMap class="w-4 h-4" />

        <div class="lh-1">
          <span> {{ $t('Process Map') }}</span>
        </div>
      </div>

      <div class="flex items-center" />
    </div>

    <div class="h-100">
      <b-container
        fluid
        ref="container"
        id="modeler-app"
        class="h-100 position-relative p-0">
        <b-card no-body class="h-100 rounded-0">
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
        v-if="showButtons"
        class="justify-end"
        :show-back-button="hasPrevNode"
        :show-next-button="hasNextNode"
        @prev="onPrev"
        @next="onNext" />
    </Teleport>
  </div>
</template>

<script>
import {
  defineComponent, ref, onMounted, watch, nextTick,
} from 'vue';
import { Modeler } from '@processmaker/modeler';

import { IMap } from '../icons';
import Teleport from '../sharedComponents/Teleport.vue';
import ButtonsNavigation from './ButtonsNavigation.vue';

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
    IMap,
    Teleport,
    ButtonsNavigation,
  },
  setup(props, { emit, expose }) {
    const validationErrors = ref();
    const xmlManager = ref();
    const modeler = ref();
    const container = ref();
    const resizer = ref();
    const count = ref(0);

    const decorations = ref({
      borderOutline: {},
    });
    const showButtons = ref(false);
    const { node, panelConfig, alternative } = props.useSlideShow;
    const {
      init, next, prev, getCompletedNodes, getLastNodesAfterBack, hasPrevNode, hasNextNode, reset, getAllNodes,
    } = props.useNavigation;

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

    const findMapElement = (id) => modeler.value.definitions.rootElements[0].flowElements.find((e) => e.id == id);

    const onPrev = async () => {
      const prevNode = await prev();

      if (prevNode) {
        const lastNodes = getLastNodesAfterBack();

        colorizeElement(lastNodes, colors.NORMAL);
        colorizeElement(prevNode.id, colors.ACTIVE);

        node.value = findMapElement(prevNode.id);
      }
    };

    const onNext = async () => {
      const nextNode = await next();

      if (nextNode) {
        const completedNodes = getCompletedNodes();

        colorizeElement(nextNode.id, colors.ACTIVE);
        colorizeElement(completedNodes, colors.COMPLETED);

        node.value = findMapElement(nextNode.id);
      }
    };

    const clickNode = async (e) => {
      if (node.value == e.node) {
        return;
      }
      // Clean all elements
      if (node.value) {
        const allNodes = getAllNodes();

        colorizeElement(allNodes, colors.NORMAL);

        reset();
      }

      node.value = e.node;

      const element = await init(e.node.id, alternative.value);

      colorizeElement(element.id, colors.ACTIVE);

      // Show two panels
      panelConfig.value = 'two-panels';
      showButtons.value = true;
    };

    watch(() => alternative.value, () => {
      modeler.value.reset({ readOnly: true });

      nextTick(() => {
        modeler.value.loadXML(alternative.value === 'A' ? window.ProcessMaker.modeler.xml : window.ProcessMaker.modeler.xmlB);
      });
    });

    expose(['onPrev', 'onNext', 'modeler']);

    return {
      onClose,
      onExpand,
      clickNode,
      onPrev,
      onNext,
      showButtons,
      hasPrevNode,
      hasNextNode,
      decorations,
      validationErrors,
      xmlManager,
      colorizeElement,
      modeler,
      clearColorize,
      container,
      resizer,
      alternative,
      count,
    };
  },
});
</script>

<style>
.btn-outline-gray {
  border: 1px solid var(--borders, #cdddee) !important;
}
</style>
