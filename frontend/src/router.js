import { createRouter, createWebHistory } from 'vue-router'
import DefaultLayout from './components/DefaultLayout.vue';
import Home from './pages/Home.vue';
import MyTodos from './pages/MyTodos.vue';
import Login from './pages/Login.vue';
import Register from './pages/Register.vue';
import NotFound from './pages/NotFound.vue';
import useUserStore from './store/user';

// Define your routes here
const routes = [
    {
        path: '/',
        component: DefaultLayout,
        children: [
            {path: '/', name: 'Home', component: Home},
            {path: '/home', name: 'HomeAlias', component: Home}, // HomeAlias is an alias for the Home component
            {path: '/todos', name: 'MyTodos', component: MyTodos},            
        ],
        beforeEnter: async (to, from, next) => {
            try{
                const userStore = useUserStore();
                await userStore.fetchUser();
                next();
            } catch (error) {
                next(false); // Prevent navigation if there's an error
            }
        }
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'NotFound',
        component: NotFound
    },
];

const router = createRouter({
  history: createWebHistory(),  
  routes
})

export default router;