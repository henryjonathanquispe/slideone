import { task } from '@processmaker/modeler';
import Vue from 'Vue';
import ButtonBar from './modeler-components/ButtonBar.vue';
import InspectorSlideshow from './modeler-components/InspectorSlideshow.vue';
import './package.css';

Vue.component('InspectorSlideshow', InspectorSlideshow);

window.ProcessMaker.EventBus.$on('modeler-init', ({ registerStatusBar, registerInspectorExtension }) => {
  registerStatusBar({ button: ButtonBar });

  registerInspectorExtension(task, {
    component: 'InspectorSlideshow',
    config: {
      label: 'Image Slide Show',
      helper: 'Test',
    },
    name: 'imageSlideShow',
  });
});
