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
    const currentElement = ref(null);

    const onExpand = () => {
      emit('expand');
    };

    const onClose = () => {
      emit('close');
    };

    const onPrev = () => {
    };

    const onNext = async () => {
      let response = null;
      if (currentElement.value.type === 'task') {
        // Call to completeToken
        response = await window.ProcessMaker.apiClient.post(`/package-testing/runs/${currentElement.value.run_id}/tokens/${currentElement.value.token_id}/complete`, {
          data: {},
        });
      }
      if (currentElement.value.type === 'exclusiveGateway') {
        response = await window.ProcessMaker.apiClient.post(`/package-testing/runs/${currentElement.value.run_id}/select-route`, {
          "gateway_id": currentElement.value.id,
          "flow_id": currentElement.value.nextFlows[0],
        });
      }
      currentElement.value = response.data.trace.find((element) => element.status === 'ACTIVE' || element.status === 'INCOMING');
    };

    const callTestEngine = async (processId, initialNode, alternative) => {
      // Call to the test engine to start the process at this point
      const response = await window.ProcessMaker.apiClient.post('/package-testing/run', {
        "demo_mode": 1,
        "scenario": null,
        "starting_step": initialNode,
        "process_id": processId,
        "data": "[]",
        "initial_data": "{}",
        "stop_step": initialNode,
        "save_test": 1,
        "auto_submit": 1,
        "bypass_script_connector": 1,
        "alternative": alternative,
      });
      currentElement.value = response.data.trace.find((element) => element.status === 'ACTIVE' || element.status === 'INCOMING');
    };

    const clickNode = (e) => {
      node.value = e.node;

      modelerApp.value.colorizeElement(node.value, colors.ACTIVE);

      // Call to the test engine to start the process at this point
      const initialNode = e.node.id;
      callTestEngine(
        window.ProcessMaker.modeler.process.id,
        initialNode,
        "A",
      );

      emit('clickNode', e);
    };

    return {
      onClose,
      onExpand,
      clickNode,
      modelerApp,
      onPrev,
      onNext,
      callTestEngine
    };
  },
});
</script>

<style>
.btn-outline-gray {
  border: 1px solid var(--borders, #cdddee) !important;
}
</style>
