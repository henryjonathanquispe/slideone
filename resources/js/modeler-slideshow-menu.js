import { task } from '@processmaker/modeler';
import Vue from 'Vue';
import ButtonBar from './modeler-menu/ButtonBar.vue';
import InspectorSlideshow from './modeler-menu/InspectorSlideshow.vue';
import './package.css';

Vue.component('InspectorSlideShow', InspectorSlideshow);

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
