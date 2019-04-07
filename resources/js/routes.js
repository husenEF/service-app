import Home from "./components/Home.vue"
import Dashboard from './components/view/Dashboard/Index.vue'
import Login from "./components/view/auth/Login.vue"

//user
import User from "./components/view/User/Index.vue"
//vehivle
import Vehicle from "./components/view/Vehicle/Index.vue"


export const routes = [
    {
        path: "/login",
        component: Login
    },
    {
        path: "/",
        component: Dashboard,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: "/home",
        component: Home
    },
    {
        path: "/user",
        component: User,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: "/vehicle",
        component: Vehicle,
        meta: {
            requiresAuth: true
        },
        children:{
            
        }
    }
]