<template>
  <b-container fluid id="modeler-app" class="h-100 position-relative p-0">
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
          @click="handleClick"
        />
      </b-card-body>
    </b-card>
  </b-container>
</template>

<script>
import { Modeler, ValidationStatus } from "@processmaker/modeler";
import { defineComponent, ref, onMounted } from "vue";

const reader = new FileReader();

const nonSelectedOpacity = 0.6;
const nonPassedWidth = 4;

const colors = {
  COMPLETED: {
    highlighter: {
      name: "stroke",
      options: {
        padding: 10,
        attrs: {
          stroke: "#00BF9C",
          "stroke-width": nonPassedWidth,
          opacity: nonSelectedOpacity,
        },
      },
    },
  },
  ERROR: {
    highlighter: {
      name: "stroke",
      options: {
        padding: 10,
        attrs: {
          stroke: "#ED4757",
          "stroke-width": nonPassedWidth,
          opacity: nonSelectedOpacity,
        },
      },
    },
  },
  LOOP: {
    highlighter: {
      name: "stroke",
      options: {
        padding: 10,
        attrs: {
          stroke: "#17A2B8",
          "stroke-width": nonPassedWidth,
          opacity: nonSelectedOpacity,
        },
      },
    },
  },
  ACTIVE: {
    highlighter: {
      name: "stroke",
      options: {
        padding: 10,
        attrs: {
          stroke: "#15A6FB",
          "stroke-width": nonPassedWidth,
          opacity: nonSelectedOpacity,
        },
      },
    },
  },
  ROUTED: {
    highlighter: {
      name: "stroke",
      options: {
        padding: 10,
        rx: 5,
        ry: 5,
        attrs: {
          "stroke-width": 5,
          stroke: "#00BF9C",
          opacity: nonSelectedOpacity,
        },
      },
    },
  },
  ROUTE_ERROR: {
    highlighter: {
      name: "stroke",
      options: {
        padding: 10,
        rx: 5,
        ry: 5,
        attrs: {
          "stroke-width": 5,
          stroke: "#ED4757",
          opacity: nonSelectedOpacity,
        },
      },
    },
  },
};

export default defineComponent({
  name: "ModelerApp",
  components: {
    Modeler,
  },
  emits: ["clickNode"],
  setup(props, { emit }) {
    const validationErrors = ref();
    const xmlManager = ref();
    const modeler = ref();

    const decorations = ref({
      borderOutline: {},
    });

    onMounted(() => {});

    const handleClick = (e) => {
      emit("clickNode", e);
      colorizeElement(e.node.id, colors.ACTIVE);
    };

    const saveBpmn = () => {};

    const colorizeElement = (nodeId, color) => {
      modeler.value.$parent.$set(
        modeler.value.$parent.decorations.borderOutline,
        nodeId,
        color
      );
    };

    const clearColors = () => {
      modeler.value.$parent.$set(
        modeler.value.$parent.decorations,
        "borderOutline",
        {}
      );
    };

    return {
      decorations,
      validationErrors,
      xmlManager,
      saveBpmn,
      handleClick,
      colorizeElement,
      modeler,
      clearColors,
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

<style lang="scss"></style>
