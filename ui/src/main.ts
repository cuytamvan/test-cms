import { createApp } from 'vue';
import { createPinia } from 'pinia';

import Router from './router';

import App from './App.vue';
import Components from './components';
import Boots from './boots';

import './assets/scss/main.scss';

const app = createApp(App);

app.use(Router);
app.use(createPinia());

Components.forEach((r) => app.component(r.name, r.component));
Boots.forEach((boot) => boot(app, Router));

app.mount('#app');
