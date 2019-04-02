
require('./bootstrap');

// window.Vue = require('vue');

import Vue from 'vue'
import VueRouter from 'vue-router'

import { routes } from "./routes"
import MainApp from './components/MainApp.vue'


Vue.use(VueRouter)

const router = new VueRouter({
    routes,
    node: 'history'
})

initialize(router)

const app = new Vue({
    el: '#app',
    router,
    components:{
        MainApp
    }
});
