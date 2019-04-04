
require('./bootstrap');
// window.Vue = require('vue');

import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuex from 'vuex'

import { routes } from "./routes"
import MainApp from './components/MainApp.vue'
import StoreData from "./store"
import { initialize } from "./helpers/general"

Vue.use(VueRouter)
Vue.use(Vuex)

const store = new Vuex.Store(StoreData)
const router = new VueRouter({
    routes,
    node: 'history'
})

initialize(store, router)

const app = new Vue({
    el: '#app',
    router,
    components: {
        MainApp,
    }
});
