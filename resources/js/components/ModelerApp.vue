<template>
  <b-container
    fluid
    ref="container"
    id="modeler-app"
    class="h-100 position-relative p-0">
    <b-card no-body class="h-100 rounded-0">
      <b-card-body class="overflow-hidden position-relative p-0 h-100">
        <modeler
          ref="modeler"
          :show-toolbar="false"
          :read-only="true"
          @saveBpmn="saveBpmn"
          @set-xml-manager="xmlManager = $event"
          @validate="validationErrors = $event"
          @warnings="warnings = $event"
          :decorations="decorations"
          @click="handleClick" />
      </b-card-body>
    </b-card>
  </b-container>
</template>

<script>
import { Modeler } from '@processmaker/modeler';
import {
  defineComponent, ref, onMounted, onUnmounted,
} from 'vue';

const reader = new FileReader();

export default defineComponent({
  name: 'ModelerApp',
  components: {
    Modeler,
  },
  emits: ['clickNode'],
  setup(props, { emit, expose }) {
    const validationErrors = ref();
    const xmlManager = ref();
    const modeler = ref();
    const container = ref();
    const resizer = ref();

    const decorations = ref({
      borderOutline: {},
    });

    onMounted(() => {
      resizer.value = new ResizeObserver(modeler.value.handleResize);
      resizer.value.observe(container.value);
    });

    onUnmounted(() => {
      resizer.value.unobserve(container.value);
      resizer.value.disconnect();
    });

    const saveBpmn = () => {};

    const colorizeElement = (nodeArray, color) => {
      const newElements = Array.isArray(nodeArray) ? nodeArray : [nodeArray];

      newElements.forEach((node) => {
        modeler.value.$parent.$set(
          modeler.value.$parent.decorations.borderOutline,
          node.id,
          color,
        );
      });
    };

    const handleClick = (e) => {
      emit('clickNode', e);
    };

    const clearColorize = () => {
      modeler.value.$parent.$set(
        modeler.value.$parent.decorations,
        'borderOutline',
        {},
      );
    };

    expose({ colorizeElement, clearColorize });

    return {
      decorations,
      validationErrors,
      xmlManager,
      saveBpmn,
      handleClick,
      colorizeElement,
      modeler,
      clearColorize,
      container,
      resizer,
    };
  },

  data() {
    return {
      uploadedXml: null,
      xmlFile: [],
      warnings: [],
    };
  },
});
</script>
