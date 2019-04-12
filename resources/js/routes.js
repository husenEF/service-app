import Home from "./components/Home.vue"
import Dashboard from './components/view/Dashboard/Index.vue'
import Login from "./components/view/auth/Login.vue"

//user
import User from "./components/view/User/Index.vue"
//vehivle
import VehicleIndex from "./components/view/Vehicle/Index.vue"
import VehicleList from "./components/view/Vehicle/List.vue"
import VehicleDetail from "./components/view/Vehicle/Detail.vue"

//history
import HistoryIndex from "./components/view/History/Index.vue"
import HistoryList from "./components/view/History/List.vue"
import HistoryTire from "./components/view/History/Tire.vue"


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
        component: VehicleIndex,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: "/",
                component: VehicleList
            },
            {
                path: ":id",
                component: VehicleDetail
            }
        ]
    },
    {
        path: "/history",
        component: HistoryIndex,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: "/",
                component: HistoryList,
            },
            {
                path: "/tires/:id",
                component: HistoryTire
            }
        ]
    }
]