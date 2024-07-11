import Vue from 'Vue';
import './package.css';
import NeedAccess from './components/screens/WithoutPermissions.vue';

new Vue({
  el: '#app-package-access-denied',
  components: { NeedAccess },
});