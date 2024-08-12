<template>
  <b-card
    no-body
    :id="'cardDocumentation-' + options.id"
    class="border-0 bg-transparent mb-3"
    v-if="options.type">
    <div class="d-flex card-styling w-100">
      <div class="card-number">
        <div class="icon">
          {{ options.number ?? '' }}
        </div>
      </div>
      <div class="p-3 card-documentation flex-grow-1 border border-light-blue">
        <div class="d-flex justify-content-between">
          <div class="mt-1 element-type d-flex align-items-center">
            <img
              v-show="iconType"
              class="mr-1 bpmn-icon"
              :src="iconType"
              :alt="$t('BPMN icon')">
            <span class="text-capitalize pr-2">
              {{ elementTypeText }}
            </span>
          </div>
          <div v-if="selectable">
            <b-form-checkbox size="lg" v-model="selected" />
          </div>
        </div>
        <div class="mt-1 element-title">
          {{ options.title }}
        </div>
        <div
          v-show="!showEditHtml && text"
          @click="showEditHtml=(editable ? true : false)"
          class="mb-2 element-description"
          :class="{'editable': editable}"
          v-html="text" />
      </div>
    </div>
  </b-card>
</template>

<script>
import { escape as lodashEscape } from 'lodash';
import actionsByEmailIcon from ('../../svg/bpmnIcons/action-by-email.svg');
import bamboohrIcon from ('../../svg/bpmnIcons/bamboohr.svg');
import dataConnectorIcon from ('../../svg/bpmnIcons/data-connector.svg');
import dataObjectIcon from ('../../svg/bpmnIcons/data-object.svg');
import dataStoreIcon from ('../../svg/bpmnIcons/data-store.svg');
import docusignIcon from ('../../svg/bpmnIcons/docusign.svg');
import endEventIcon from ('../../svg/bpmnIcons/end-event.svg');
import flowgenieIcon from ('../../svg/bpmnIcons/flowgenie.svg');
import gatewayIcon from ('../../svg/bpmnIcons/gateway.svg');
import genericGatewayIcon from ('../../svg/bpmnIcons/generic-gateway.svg');
import githubIcon from ('../../svg/bpmnIcons/github.svg');
import idpIcon from ('../../svg/bpmnIcons/idp.svg');
import intermediateEventIcon from ('../../svg/bpmnIcons/intermediate-event.svg');
import pdfIcon from ('../../svg/bpmnIcons/pdf.svg');
import poolIcon from ('../../svg/bpmnIcons/pool.svg');
import sendEmailIcon from ('../../svg/bpmnIcons/send-email.svg');
import slackNotificationIcon from ('../../svg/bpmnIcons/slack-notification.svg');
import slackIcon from ('../../svg/bpmnIcons/slack.svg');
import startEventIcon from ('../../svg/bpmnIcons/start-event.svg');
import tableIcon from ('../../svg/bpmnIcons/table.svg');
import taskIcon from ('../../svg/bpmnIcons/task.svg');
import textAnnotationIcon from ('../../svg/bpmnIcons/text-annotation.svg');
import gatewayIcon from ('../../svg/bpmnIcons/generic-gateway.svg');

export default {
  name: 'CardDocumentation',
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
      default: () => ({
        id: '',
        type: '',
        title: '',
        documentation: '',
      }),
    },
  },
  data() {
    return {
      showEditHtml: true,
      selected: true,
      text: 'JONAS',
      actionsByEmailIcon, 
      bamboohrIcon, 
      dataConnectorIcon, 
      dataObjectIcon, 
      dataStoreIcon, 
      docusignIcon, 
      endEventIcon, 
      flowgenieIcon, 
      gatewayIcon, 
      genericGatewayIcon, 
      githubIcon, 
      idpIcon, 
      intermediateEventIcon, 
      pdfIcon, 
      poolIcon, 
      sendEmailIcon, 
      slackNotificationIcon, 
      slackIcon, 
      startEventIcon, 
      tableIcon, 
      taskIcon, 
      textAnnotationIcon, 
      gatewayIcon, 
      originalText: '',
    };
  },
  computed: {
    iconType() {
      switch (this.options.type) {
        case 'start event':
          return this.startEventIcon;
        case 'end event':
          return this.endEventIcon;
        case 'intermediate event':
        case 'intermediate timer event':
        case 'intermediate catch event':
        case 'intermediate throw event':
        case 'intermediate message catch event':
        case 'intermediate message throw event':
        case 'intermediate conditional catch event':
          return this.intermediateEventIcon;
        case 'task':
          return this.getCalledElementIcon();
        case 'service task':
          return this.getImplementationIcon();
        case 'script task':
          return this.taskIcon;
        case 'exclusive gateway':
        case 'parallel gateway':
        case 'inclusive gateway':
        case 'event based gateway':
          return this.gatewayIcon;
        case 'data store reference':
          return this.dataStoreIcon;
        case 'data object reference':
          return this.dataObjectIcon;
        default:
          return '';
      }
    },
    elementTypeText() {
      if (this.options.calledElement === 'ProcessId-DocuSignSendEnvelope') {
        return this.$t('DocuSign');
      }
      switch (this.options.implementation) {
        case 'connector-send-email/processmaker-communication-email-send':
          return this.$t('Send Email Task');
        case 'connector-slack/processmaker-communication-slack':
          return this.$t('Slack Connector');
        case 'package-decision-engine/decision-table':
          return this.$t('Decision Table');
        case 'package-rpa/processmaker-communication-rpa':
          return this.$t('Communication RPA');
        case 'package-data-sources/data-source-task-service':
          return this.$t('Data Source');
        case 'package-ai/processmaker-ai-task':
        case 'package-ai/processmaker-ai-assistant':
          return this.$t('FlowGenie');
        case 'connector-pdf-print/processmaker-communication-pdf-print':
          return this.$t('PDF Connector');
        case 'connector-idp/processmaker-communication-idp':
          return this.$t('IDP Connector');
        default:
          return this.options.type.includes('intermediate')
            ? this.$t('Intermediate Event')
            : this.options.type;
      }
    },
  },
  watch: {
    'options.documentation': {
      immediate: true,
      handler(value) {
        this.text = value;
      },
    },
    'options.selected': {
      immediate: true,
      handler(value) {
        this.selected = value;
      },
    },
    text: {
      immediate: true,
      handler(value) {
        this.$emit('input', value);
      },
    },
    selected: {
      immediate: true,
      handler(value) {
        this.$emit('selected', value);
      },
    },
    showEditHtml: {
      handler(value) {
        // If change to true
        if (value) {
          this.originalText = this.text;
        }
      },
    },
  },
  methods: {
    save: () => {
      this.$emit('save');
    },
    safeString: (string) => {
      if (string) {
        return lodashEscape(string.replace(/<\/[^>]+(>|$)/g, ''));
      }
      return '';
    },
    getImplementationIcon() {
      switch (this.options.implementation) {
        case 'connector-send-email/processmaker-communication-email-send':
          return this.sendEmailIcon;
        case 'connector-slack/processmaker-communication-slack':
          return this.slackIcon;
        case 'package-decision-engine/decision-table':
          return this.tableIcon;
        case 'package-rpa/processmaker-communication-rpa':
          return this.taskIcon;
        case 'package-data-sources/data-source-task-service':
          return this.dataConnectorIcon;
        case 'package-ai/processmaker-ai-task':
        case 'package-ai/processmaker-ai-assistant':
          return this.flowgenieIcon;
        case 'connector-pdf-print/processmaker-communication-pdf-print':
          return this.pdfIcon;
        case 'connector-idp/processmaker-communication-idp':
          return this.idpIcon;
        default:
          return this.taskIcon;
      }
    },
    getCalledElementIcon() {
      if (this.options.calledElement === 'ProcessId-DocuSignSendEnvelope') {
        return this.docusignIcon;
      }
      if (this.options.implementation === 'Actions By Email') {
        return this.actionsByEmailIcon;
      }
      return this.taskIcon;
    },
    onCancel() {
      this.text = this.originalText;
      this.showEditHtml = false;
    },
  },
};
</script>
<style scoped>
.card-styling {
  min-height: 140px;
}

.card-number {
  display: flex;
  background: #cdddee;
  width: 50px;
  justify-content: center;
  align-items: center;
  padding: 0 8px;
  gap: 10px;
  order: 0;
  border-radius: 10px 0 0 10px;
}

.icon {
  background: #1572c2;
  color: white;
  font-family: "Open Sans", sans-serif;
  font-size: 20px;
  font-weight: 700;
  width: 35px;
  height: 35px;
  align-items: center;
  text-align: center;
  vertical-align: middle;
  border-radius: 100%;
  padding-top: 2px;
}

.bpmn-icon {
  width: 1.25em;
}

.card-documentation {
  background: white;
  border-radius: 0 10px 10px 0;
}

.element-type {
  display: inline-block;
  text-align: left;
  font-family: "Open Sans", serif;
  font-style: normal;
  font-weight: 400;
  letter-spacing: -0.02em;
  color: #556271;
  padding: 5px 8px;
  gap: 8px;
  background: #ebeff4;
  border-radius: 4px;
}

.element-title {
  height: 36px;
  padding: 0.5rem 0 0 0.5rem;
  font-family: "Open Sans", serif;
  font-style: normal;
  font-weight: 600;
  font-size: 18px;
  line-height: 25px;
  letter-spacing: -0.02em;
  color: #556271;
  flex: none;
  order: 1;
  align-self: stretch;
  flex-grow: 0;
}

.element-description {
  font-family: "Open Sans", serif;
  font-style: normal;
  font-weight: 400;
  letter-spacing: -0.02em;
  padding-left: 0.5rem;
  color: #556271;
  min-height: 50px;
}
.max-width-1200 {
  max-width: 1200px;
}
.border.border-light-blue {
  border-color:#cdddee !important;
}
.edit-documentation-placeholder, .element-description {
  padding: 0.5rem;
  border-radius: 5px;
  cursor: pointer;
}
.edit-documentation-placeholder {
  color: #556271b0;
}
.edit-documentation-placeholder:hover, .element-description:hover {
  background: #f6f9fb;
}
</style>
