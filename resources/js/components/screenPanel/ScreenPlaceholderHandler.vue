<template>
  <div
    class="flex flex-col w-full items-center"
    :class="{
      'justify-center': !documentation
    }">
    <div class="h-auto bg-white w-full">
      <GatewayScreen
        v-if="renderScreen === 'GatewayScreen'"
        :title="node.name"
        :options="options"
        :use-navigation="useNavigation"
        :use-slide-show="useSlideShow"
        :subtitle="$t('Choose a path from the gateway to continue the flow')" />
      <component
        v-else
        :use-navigation="useNavigation"
        :use-slide-show="useSlideShow"
        :is="renderScreen" />

      <CardDocumentation v-if="documentation" class="text-gray-500 rounded-l-xl rounded-r-xl border-x border-y border-gray-300 mx-4 my-4" :node="node" />
    </div>
  </div>
</template>

<script>
import { defineComponent, computed } from 'vue';
import DontSupportScreen from '../placeholderScreens/DontSupportScreen.vue';
import EndEventScreen from '../placeholderScreens/EndEventScreen.vue';
import StartEventScreen from '../placeholderScreens/StartEventScreen.vue';
import GatewayScreen from '../placeholderScreens/GatewayScreen.vue';
import NoScreenAssigned from '../placeholderScreens/NoScreenAssigned.vue';
import SubprocessScreen from '../placeholderScreens/SubprocessScreen.vue';
import IDPScreen from '../placeholderScreens/IDPScreen.vue';
import DocuSignScreen from '../placeholderScreens/DocuSignScreen.vue';
import PDFGeneratorScreen from '../placeholderScreens/PDFGeneratorScreen.vue';
import SendEmailScreen from '../placeholderScreens/SendEmailScreen.vue';
import ActionByEmailScreen from '../placeholderScreens/ActionByEmailScreen.vue';
import FlowGenieScreen from '../placeholderScreens/FlowGenieScreen.vue';
import DataConnectorScreen from '../placeholderScreens/DataConnectorScreen.vue';
import DecisionTaskScreen from '../placeholderScreens/DecisionTaskScreen.vue';
import ScriptTaskScreen from '../placeholderScreens/ScriptTaskScreen.vue';
import IntermediateEventScreen from '../placeholderScreens/IntermediateEventScreen.vue';
import CardDocumentation from './CardDocumentation.vue';

export default defineComponent({
  components: {
    DontSupportScreen,
    EndEventScreen,
    StartEventScreen,
    GatewayScreen,
    NoScreenAssigned,
    SubprocessScreen,
    IDPScreen,
    DocuSignScreen,
    PDFGeneratorScreen,
    SendEmailScreen,
    ActionByEmailScreen,
    FlowGenieScreen,
    DataConnectorScreen,
    DecisionTaskScreen,
    ScriptTaskScreen,
    IntermediateEventScreen,
    CardDocumentation,
  },
  props: {
    node: {
      type: Object,
      default: () => ({}),
    },
    useNavigation: {
      type: Object,
      default: () => {},
    },
    useSlideShow: {
      type: Object,
      default: () => {},
    },
  },
  setup(props) {
    const { getCurrentGatewayOptions } = props.useNavigation;
    const { findMapElement } = props.useSlideShow;

    const renderScreen = computed(() => {
      const screens = {
        GatewayScreen: ['Gateway'],
        StartEventScreen: ['Start'],
        EndEventScreen: ['End'],
        ScriptTaskScreen: ['ScriptTask'],
        DontSupportScreen: ['Task'],
        IntermediateEventScreen: ['Intermediate'],
        DecisionTaskScreen: ['decision-table'],
        DataConnectorScreen: ['data-source-task'],
        FlowGenieScreen: ['processmaker-ai-task'],
        ActionByEmailScreen: ['Action'],
        SendEmailScreen: ['processmaker-communication-email-send'],
        PDFGeneratorScreen: ['processmaker-communication-pdf-print'],
        DocuSignScreen: ['ProcessId-DocuSignSendEnvelope'],
        IDPScreen: ['processmaker-communication-idp'],
        SubprocessScreen: ['CallActivity'],
      };

      const response = Object.entries(screens).find(([key, value]) => {
        const res = value.some((e) => {
          if (e == 'CallActivity' && props.node?.calledElement) {
            return true;
          }

          if (props.node?.calledElement) {
            return props.node?.calledElement.includes(e);
          }

          if (props.node?.implementation) {
            return props.node?.implementation.includes(e);
          }

          return props.node?.$type?.includes(e);
        });

        return res;
      });

      return response ? response[0] : 'DontSupportScreen';
    });

    const buildGatewayOptions = (flows) => flows.map((e) => ({
      label: e.name || e.id,
      value: e.id,
    }));

    const options = computed(() => {
      if (props.node?.$type.includes('Gateway')) {
        const flows = getCurrentGatewayOptions().map((e) => findMapElement(e));

        return buildGatewayOptions(flows);
      }
      return [];
    });

    const documentation = computed(() => {
      if (props.node.documentation && props.node.documentation[0]) {
        return props.node.documentation[0].text;
      }

      return null;
    });

    return {
      renderScreen,
      options,
      documentation,
    };
  },
});
</script>
