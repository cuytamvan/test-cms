import { ComponentCustomProperties } from 'vue';

import config from './config';
import helper from './helper';

declare module 'vue' {
  interface ComponentCustomProperties {
    $config: typeof config;
    $helper: typeof helper;
  }
}
