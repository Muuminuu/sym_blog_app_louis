import { createApp } from 'vue'

const app = createApp({
  /* root component options */
})

document.addEventListener('vue:before-mount', (event) => {ication instance

    // Example with Vue Router
    const router = VueRouter.createRouter({
        history: VueRouter.createWebHashHistory(),
        routes: [
            /* ... */
        ],
    });

    app.use(router);
});