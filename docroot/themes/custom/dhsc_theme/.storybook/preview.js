import '../src/scss/tailwind.scss';
import '../dist/css/components.css';
import '../dist/css/global.css';
import 'alpinejs';
import Twig from 'twig';
import { setupTwig } from './setupTwig';
import './drupalBehaviors';

setupTwig(Twig);

const preview = {
  parameters: {
    actions: { argTypesRegex: "^on[A-Z].*" },
    controls: {
      matchers: {
        color: /(background|color)$/i,
        date: /Date$/,
      },
    },
  },
};

export default preview;
