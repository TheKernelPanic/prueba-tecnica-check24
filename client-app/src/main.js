import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import {createRouter, createWebHistory} from "vue-router";
import routes from "./routes.js";
import {createPinia} from "pinia";

createApp(App)
    .use(
        createRouter({
            history: createWebHistory('/'),
            routes,
            scrollBehavior: (from, to, savedPosition) => {
                return {top: 0}
            }
        })
    )
    .use(
        createPinia()
    )
    .mount('#app')
