import '../src/scss/tailwind.scss';
import Twig from 'twig';
import { setupTwig } from './setupTwig';

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
