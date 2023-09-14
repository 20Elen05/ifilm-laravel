import {createApp} from 'vue'
import router from './routes/routes.js'
import store from './store.js';
import App from './App.vue'
import axios from "axios";
import './bootstrap.js';

const app = createApp(App).use(router).use(store).mount("#app")

