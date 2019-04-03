import Vue from 'vue'

const app = new Vue({

    // Initiate app ID
    el: '#app',
    components: {
      'example': require('./components/example.vue').default
    }
});
