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
      <ModelerApp ref="modelerApp" id="modeler-app" @clickNode="clickNode" />
    </div>

    <Teleport to=".rail-container">
      <ButtonsNavigation class="justify-end" @prev="onPrev" @next="onNext" />
    </Teleport>
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue';
import ModelerApp from './ModelerApp';
import { IMap } from '../icons';
import Teleport from '../sharedComponents/Teleport.vue';
import ButtonsNavigation from './ButtonsNavigation.vue';

import { useNavigation as useNavComposable } from './useNavigation';

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
  props: ['useSlideShow'],
  components: {
    ModelerApp,
    IMap,
    Teleport,
    ButtonsNavigation,
  },
  setup(props, { emit }) {
    const modelerApp = ref();
    const { node } = props.useSlideShow;

    const useNavigation = useNavComposable();
    const {
      currentElement, history, init, next, prev, getCompletedNodes,
    } = useNavigation;

    const onExpand = () => {
      emit('expand');
    };

    const onClose = () => {
      emit('close');
    };

    const onPrev = (e) => {
      prev();
    };

    const findMapElement = (id) => modelerApp.value.modeler.definitions.rootElements[0].flowElements.find((e) => e.id == id);

    const onNext = async () => {
      const nextNode = await next();

      if (nextNode) {
        const completedNodes = getCompletedNodes();

        modelerApp.value.colorizeElement(nextNode.id, colors.ACTIVE);
        modelerApp.value.colorizeElement(completedNodes, colors.COMPLETED);

        node.value = findMapElement(nextNode.id);
      }
    };

    const clickNode = async (e) => {
      node.value = e.node;

      const element = await init(e.node.id);

      modelerApp.value.colorizeElement(element.id, colors.ACTIVE);

      const mapElement = findMapElement(element.id);

      emit('clickNode', mapElement);
    };

    return {
      onClose,
      onExpand,
      clickNode,
      modelerApp,
      onPrev,
      onNext,
    };
  },
});
</script>

<style>
.btn-outline-gray {
  border: 1px solid var(--borders, #cdddee) !important;
}
</style>
