import ButtonBarCustom from './modeler-components/ButtonBarCustom.vue';
import './package.css';

console.log('modeler-slideone-menu.js');
window.ProcessMaker.EventBus.$on('modeler-init', ({ registerStatusBar }) => {
  registerStatusBar({ button: ButtonBarCustom });
});
