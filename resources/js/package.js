import Vue from 'Vue';
import SlideShow from './SlideShow.vue';
import './package.css';
import storeModule from './store';
import { getImages } from './utils/slideshowApi';

const { modeler } = window.ProcessMaker;

window.ProcessMaker.EventBus.$on('modeler-start', ({ loadXML }) => {
  loadXML(window.ProcessMaker.modeler.published_version === 'B' ? window.ProcessMaker.modeler.xmlB : window.ProcessMaker.modeler.xml);
});

if (Vue.globalStore) {
  Vue.globalStore.registerModule('slideshow', storeModule);

  getImages(modeler.process.id).then((response) => {
    if (response.data) {
      Vue.globalStore.commit('slideshow/setImages', response.data[0].media);
    }
  });
}

new Vue({
  el: '#app-package-show',
  components: { SlideShow },
});
