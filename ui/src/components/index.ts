import { kebabCase } from 'lodash';

const ImportComponents = import.meta.globEager('./_global/*.vue');

interface MapComponents {
  name: string;
  component: any;
}

const Components: Array<MapComponents> = Object.entries(ImportComponents).map(([path, definition]) => {
  const componentName = kebabCase(
    path
      .split('/')
      ?.pop()
      ?.replace(/\.\w+$/, '')
  );

  return { name: componentName, component: definition.default };
});

export default Components;
