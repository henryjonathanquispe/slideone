import Vue from 'Vue';
import SlideShow from './SlideShow.vue';
import './package.css';

window.ProcessMaker.EventBus.$on('modeler-start', ({ loadXML }) => {
  loadXML(window.ProcessMaker.modeler.xml);
});

new Vue({
  el: '#app-package-show',
  components: { SlideShow },
});
