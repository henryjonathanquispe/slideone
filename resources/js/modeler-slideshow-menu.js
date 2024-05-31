import ButtonBar from "./components/ButtonBar.vue";

window.ProcessMaker.EventBus.$on("modeler-init", ({ registerStatusBar }) => {
  registerStatusBar({ button: ButtonBar });
});
