import Vue from 'vue'
import App from './App.vue'

Vue.config.productionTip = false

import 'datatables.net-buttons-dt';
import 'datatables.net-buttons/js/buttons.colVis.mjs';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-select-dt';
import 'datatables.net-datetime';
import pdfMake from "pdfmake/build/pdfmake";
import pdfFonts from "pdfmake/build/vfs_fonts";
pdfMake.addVirtualFileSystem(pdfFonts);

const Emitter = require('tiny-emitter');
const emitter = new Emitter();

window.Bus = emitter;

window.$ = window.jQuery = require('jquery');

new Vue({
  render: h => h(App),
}).$mount('#app')
