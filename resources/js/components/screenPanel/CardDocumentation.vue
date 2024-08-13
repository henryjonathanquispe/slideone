<template>
  <div class="bg-white space-y-2 px-3 py-3">
    <div class="flex inline-flex items-center bg-gray-100 text-sm px-2 py-1 rounded-l-md rounded-r-md space-x-2">
      <i :class="iconType" />
      <span>{{ node.$type }}</span>
    </div>

    <div class="font-bold text-xl">
      {{ node.name }}
    </div>

    <div
      v-html="documentation" />
  </div>
</template>

<script>
import { defineComponent, computed } from 'vue';

export default defineComponent({
  props: {
    selectable: {
      type: Boolean,
      default: false,
    },
    editable: {
      type: Boolean,
      default: true,
    },
    node: {
      type: Object,
      default: () => { },
    },
  },
  setup(props) {
    const icons = {
      StartEvent: 'fp-bpmn-start-event',
      EndEvent: 'fp-bpmn-end-event',
      Intermediate: 'fp-bpmn-intermediate-event',
      'ProcessId-DocuSignSendEnvelope': 'fp-bpmn-docusign',
      'Actions By Email': 'fp-bpmn-action-by-email',
      Task: 'fp-bpmn-task',
      'connector-send-email/processmaker-communication-email-send': 'fp-bpmn-send-email',
      'connector-slack/processmaker-communication-slack': 'fp-slack',
      'package-decision-engine/decision-table': 'fp-table',
      'package-rpa/processmaker-communication-rpa': 'fp-bpmn-task',
      'package-data-sources/data-source-task-service': 'fp-bpmn-data-connector',
      'package-ai/processmaker-ai-task': 'fp-bpmn-flowgenie',
      'package-ai/processmaker-ai-assistant': 'fp-bpmn-flowgenie',
      'connector-pdf-print/processmaker-communication-pdf-print': 'fp-pdf',
      'connector-idp/processmaker-communication-idp': 'fp-bpmn-idp',
      Gateway: 'fp-bpmn-gateway',
      'data store reference': 'fp-bpmn-data-store',
      'data object reference': 'fp-bpmn-object-store',
    };

    const iconType = computed(() => {
      if (!props.node?.$type) {
        return null;
      }

      if (props.node?.$type.includes('ServiceTask')) {
        if (props.node?.implementation) {
          return icons[props.node?.implementation] || 'fp-bpmn-task';
        }

        return null;
      }

      const result = Object.keys(icons).find((key) => props.node.$type.includes(key));

      return icons[result] || 'fp-bpmn-task';
    });

    const documentation = computed(() => {
      if (props.node.documentation && props.node.documentation[0]) {
        return props.node.documentation[0].text;
      }

      return null;
    });

    return {
      documentation,
      iconType,
    };
  },
});
</script>
