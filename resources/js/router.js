import { createRouter, createWebHistory } from "vue-router";
import Home from "../components/pages/Home.vue";
import LoginAndRegister from "../components/pages/LoginAndRegister.vue";
import store from "./store/index";

const routes = [
    {
        path: "/",
        name: "home",
        component: Home,
        meta: {
            middleware: "auth",
            title: `Home`,
        },
    },
    {
        path: "/login",
        name: "loginAndRegister",
        component: LoginAndRegister,
        meta: {
            middleware: "guest",
            title: `Login`,
        },
    },
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
});

router.beforeEach((to, from, next) => {
    document.title = to.meta.title;
    if (to.meta.middleware == "guest") {
        if (store.state.auth.authenticated) {
            next({ name: "home" });
        }
        next();
    } else {
        if (store.state.auth.authenticated) {
            next();
        } else {
            next({ name: "loginAndRegister" });
        }
    }
});

export default router;
