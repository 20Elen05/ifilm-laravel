import { createWebHistory, createRouter } from "vue-router";
import signUp  from "@/components/signUp.vue";

const authMiddleware = (to, from, next) => {
    if ( !localStorage.getItem('token') && (to.name !== 'signIn' || to.name !== 'signUp')) {
        next({
            path: 'signIn',
            replace: true
        })
    } else {
        next()
    }
}
const notAuthMiddleware = (to, from, next) => {
    if ( localStorage.getItem('token')) {
        next({
            path: '/standart',
            replace: true
        })
    } else {
        next()
    }
}

const routes = [
    {path: "/", component: signUp,beforeEnter:notAuthMiddleware},
    {path: '/signIn', component: () => import('../components/signIn.vue'), name: 'signIn',beforeEnter:notAuthMiddleware },
    {path: '/standart', component: () => import('../components/standart.vue'), name: 'standart',beforeEnter:authMiddleware  },
    {path: '/movie/:id', component:() => import('../components/movie.vue'), name:'movie',beforeEnter:authMiddleware },
    {path: '/search/:keyword', component: () => import('../components/search.vue'), name: 'search' ,beforeEnter:authMiddleware },
    {path: '/profile', component: () => import ('../components/profile.vue'), name: 'profile',beforeEnter:authMiddleware },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
