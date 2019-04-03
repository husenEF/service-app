import Home from "./components/Home.vue"
import Dashboard from './components/view/Dashboard/Index.vue'

export const routes = [
    {
        path: "/",
        component: Dashboard
    },
    {
        path: "/home",
        component: Home
    }
]