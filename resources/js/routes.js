import Home from "./components/Home.vue"
import Dashboard from './components/view/Dashboard/Index.vue'
import Login from "./components/view/auth/Login.vue"

//user
import UserIndex from "./components/view/User/Index.vue"
import UserList from "./components/view/User/List.vue"
import UserAdd from "./components/view/User/Add.vue"
import UserEdit from "./components/view/User/Edit.vue"
import UserProfile from "./components/view/User/Profile.vue"

//vehicle
import VehicleIndex from "./components/view/Vehicle/Index.vue"
import VehicleList from "./components/view/Vehicle/List.vue"
import VehicleEdit from "./components/view/Vehicle/Edit.vue"
import VehicleAdd from "./components/view/Vehicle/Add.vue"
import VehicleDetail from "./components/view/Vehicle/Detail.vue"
import VehicleTire from "./components/view/Vehicle/ManageTire.vue"

//tire
import TireIndex from "./components/view/Tire/Index.vue"
import TireList from "./components/view/Tire/List.vue"
import TireService from "./components/view/Tire/TireService.vue"
import TireAdd from "./components/view/Tire/Add.vue"
import TireEdit from "./components/view/Tire/Edit.vue"
import TireTrash from "./components/view/Tire/Trash.vue"
//history
import HistoryIndex from "./components/view/History/Index.vue"
import HistoryList from "./components/view/History/List.vue"
import HistoryTire from "./components/view/History/Tire.vue"
import HistoryPosition from "./components/view/History/Position.vue"

//service
import ServiceIndex from "./components/view/Service/Index.vue"
import ServiceList from "./components/view/Service/List.vue"


export const routes = [
    {
        path: "/login",
        component: Login,
        meta: {
            title: "Halaman Masuk"
        }
    },
    {
        path: "/",
        component: Dashboard,
        meta: {
            requiresAuth: true,
            title: "Halaman Utama",
        }
    },
    {
        path: "/home",
        component: Home,
        meta: {
            title: "Home Page"
        }
    },
    {
        path: "/my-account",
        component: UserProfile,
        meta: {
            title: "My Account Detail"
        }
    },
    {
        path: "/user",
        component: UserIndex,
        meta: {
            requiresAuth: true,
            title: "Daftar Pengguna"
        },
        children: [
            {
                path: "/",
                component: UserList,
                name: "userlist",
                meta: {
                    title: "Daftar Pengguna",
                    roles: "admin"
                }

            }, {
                path: "add",
                component: UserAdd,
                name: "userAdd",
                meta: {
                    title: "Tambah Pengguna",
                    roles: "admin"
                }
            }, {
                path: ":id",
                component: UserEdit,
                name: "userEdit",
                meta: {
                    title: "Rubah Pengguna",
                    roles: "admin"
                }
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
                component: VehicleList,
                meta: {
                    title: "Daftar Kendaraan"
                }
            },
            {
                path: "add",
                component: VehicleAdd,
                meta: {
                    title: "Tambah Kendaraan",
                    roles: "admin"
                }
            },
            {
                path: ":id",
                component: VehicleDetail,
                meta: {
                    title: "Detail Kendaraan"
                }
            },
            {
                path: "edit/:id",
                component: VehicleEdit,
                meta: {
                    title: "Edit Kendaraan",
                    roles: "admin"
                }
            },
            {
                path: "tire/:id",
                component: VehicleTire,
                meta: {
                    title: "Management Ban Kendaraan",
                }
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
                component: HistoryTire,
                meta: {
                    title: "Riwayat Ban"
                }
            }, {
                path: "position/:vehicle/:id",
                component: HistoryPosition,
                meta: {
                    title: "Riwayat Posisi Ban"
                }
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
                name: "tireList",
                meta: {
                    title: "Daftar Ban"
                }
            }, {
                path: "service/:tireid",
                component: TireService,
                name: "tireService",
                meta: {
                    title: "Service Ban"
                }
            },
            {
                path: "add",
                component: TireAdd,
                name: "tireAdd",
                meta: {
                    title: "Tambah Ban Baru"
                }
            }, {
                path: "edit/:id",
                component: TireEdit,
                name: "tireEdit",
                meta: {
                    title: "Edit Ban"
                }
            }, {
                path: "trash",
                component: TireTrash,
                name: "tireTrash",
                meta: {
                    title: "Trash"
                }
            }
        ]
    }, {
        path: "/service",
        component: ServiceIndex,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: "/",
                component: ServiceList,
                name: "serviceList",
                meta: {
                    title: "Riwayat Pengecekan Ban"
                }
            }
        ]
    }
]
