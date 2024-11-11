import {
  task, manualTask, startEvent, callActivity, endEvent, scriptTask,
} from '@processmaker/modeler';
import Vue from 'Vue';
import ButtonBar from './modeler-components/ButtonBar.vue';
import InspectorSlideshow from './modeler-components/InspectorSlideshow.vue';
import './package.css';
import storeModule from './store';
import { getImages } from './utils/slideshowApi';

Vue.component('InspectorSlideshow', InspectorSlideshow);

window.ProcessMaker.EventBus.$on('modeler-init', ({ registerStatusBar, registerInspectorExtension, modeler }) => {
  // Register new store module vuex
  if (Vue.globalStore) {
    Vue.globalStore.registerModule('slideshow', storeModule);

    getImages(window?.ProcessMaker?.modeler?.process?.id).then((response) => {
      if (response?.data) {
        Vue.globalStore.commit('slideshow/setImages', response.data[0].media);
      }
    });
  }

  // Register button in status bar
  registerStatusBar({ button: ButtonBar });

  // Register inspector to modeler
  const nodes = [task, manualTask, startEvent, callActivity, endEvent, scriptTask];

  const extraNodes = modeler.nodeTypes.filter((e) => ['processmaker-communication-email-send', 'processmaker-ai-task', 'data-source-task-service', 'processmaker-decision-engine-tables', 'docusign-template', 'processmaker-communication-pdf-print', 'processmaker-communication-idp'].includes(e.id));

  nodes.concat(extraNodes).forEach((node) => {
    registerInspectorExtension(node, {
      component: 'InspectorSlideshow',
      config: {
        label: 'Image Slide Show',
        helper: 'Test',
      },
      name: 'imageSlideShow',
    });
  });
});
