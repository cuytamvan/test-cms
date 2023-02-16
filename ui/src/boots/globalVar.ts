import { App } from 'vue';
import { Router } from 'vue-router';

import config from '@/services/config';
import helper from '@/services/helper';

const boot = (app: App, router: Router): void => {
  app.config.globalProperties.$config = config;
  app.config.globalProperties.$helper = helper;
};

export default boot;
