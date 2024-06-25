import { task } from '@processmaker/modeler';
import Vue from 'Vue';
import ButtonBar from './modeler-components/ButtonBar.vue';
import InspectorSlideshow from './modeler-components/InspectorSlideshow.vue';
import './package.css';
import storeModule from './store';
import { getImages } from './utils/slideshowApi';

const { modeler } = window.ProcessMaker;

Vue.component('InspectorSlideshow', InspectorSlideshow);

window.ProcessMaker.EventBus.$on('modeler-init', ({ registerStatusBar, registerInspectorExtension }) => {
  // Register new store module vuex
  if (Vue.globalStore) {
    Vue.globalStore.registerModule('slideshow', storeModule);

    getImages(modeler.process.id).then((response) => {
      if (response.data) {
        Vue.globalStore.commit('slideshow/setImages', response.data[0].media);
      }
    });
  }

  // Register button in status bar
  registerStatusBar({ button: ButtonBar });

  // Register inspector to modeler
  registerInspectorExtension(task, {
    component: 'InspectorSlideshow',
    config: {
      label: 'Image Slide Show',
      helper: 'Test',
    },
    name: 'imageSlideShow',
  });
});
