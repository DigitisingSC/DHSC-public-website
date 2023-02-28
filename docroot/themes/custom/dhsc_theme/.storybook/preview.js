// CSS imports from localgov_base - dependencies
import './../../../contrib/localgov_base/css/layout/grid.css';
import './../../../contrib/localgov_base/css/components/header.css';
import './../../../contrib/localgov_base/css/components/footer.css';
import './../../../contrib/localgov_base/css/components/callout.css';
import './../../../contrib/localgov_base/css/components/secondary-menu.css';
import './../../../contrib/localgov_base/css/components/wysiwyg-styles.css';

// CSS imports from localgov_base - styles
import './../../../contrib/localgov_base/css/base/variables.css';
import './../../../contrib/localgov_base/css/base/base.css';
import './../../../contrib/localgov_base/css/layout/layout-utilities.css';
import './../../../contrib/localgov_base/css/components/form-items.css';
import './../../../contrib/localgov_base/css/components/messages.css';

// CSS imports from dhsc_theme - styles
import './../css/base/variables.css';
import './../css/base/global.css';
import './../css/layout/layout-utilities.css';


import { INITIAL_VIEWPORTS } from '@storybook/addon-viewport';

export const parameters = {
  actions: { argTypesRegex: "^on[A-Z].*" },
  controls: {
    matchers: {
      color: /(background|color)$/i,
      date: /Date$/,
    },
  },
  viewport: {
    viewports: INITIAL_VIEWPORTS,
  },
}