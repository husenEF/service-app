import Home from "./components/Home.vue"
import Dashboard from './components/view/Dashboard/Index.vue'
import Login from "./components/view/auth/Login.vue"

//user
import UserIndex from "./components/view/User/Index.vue"
import UserList from "./components/view/User/List.vue"
import UserAdd from "./components/view/User/Add.vue"
//vehivle
import VehicleIndex from "./components/view/Vehicle/Index.vue"
import VehicleList from "./components/view/Vehicle/List.vue"
import VehicleDetail from "./components/view/Vehicle/Detail.vue"
import VehicleAdd from "./components/view/Vehicle/Add.vue"

//tire
import TireIndex from "./components/view/Tire/Index.vue"
import TireList from "./components/view/Tire/List.vue"
//history
import HistoryIndex from "./components/view/History/Index.vue"
import HistoryList from "./components/view/History/List.vue"
import HistoryTire from "./components/view/History/Tire.vue"
import HistoryPosition from "./components/view/History/Position.vue"


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
        component: UserIndex,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: "/",
                component: UserList,
                name: "userlist"
            }, {
                path: "add",
                component: UserAdd,
                name: "userAdd"
            }
        ]
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
                path: "add",
                component: VehicleAdd
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
                path: "tires/:id",
                component: HistoryTire
            }, {
                path: "position/:vehicle/:id",
                component: HistoryPosition
            }
        ]
    },
    {
        path: "/ban",
        component: TireIndex,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: "/",
                component: TireList,
                name: "tireList"
            }
        ]
    }
]