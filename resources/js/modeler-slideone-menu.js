import ButtonBar from './modeler-components/ButtonBar.vue';
import './package.css';

window.ProcessMaker.EventBus.$on('modeler-init', ({ registerStatusBar }) => {
  registerStatusBar({ button: ButtonBar });
});
