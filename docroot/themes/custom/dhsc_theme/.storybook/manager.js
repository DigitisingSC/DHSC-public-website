// .storybook/manager.js

import { addons } from '@storybook/addons';
import dsc from './dsc';

addons.setConfig({
  theme: dsc,
});
