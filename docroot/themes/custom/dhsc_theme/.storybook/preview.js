// CSS imports from localgov_theme - dependencies
import './../../../contrib/localgov_theme/assets/css/lib/main.css';

// CSS imports from localgov_base - dependencies
import './../../../contrib/localgov_base/css/layout/grid.css';
import './../../../contrib/localgov_base/css/components/header.css';
import './../../../contrib/localgov_base/css/components/footer.css';
import './../../../contrib/localgov_base/css/components/callout.css';
import './../../../contrib/localgov_base/css/components/secondary-menu.css';
import './../../../contrib/localgov_base/css/components/wysiwyg-styles.css';
//
// // CSS imports from localgov_base - styles
import './../../../contrib/localgov_base/css/base/variables.css';
import './../../../contrib/localgov_base/css/base/base.css';
import './../../../contrib/localgov_base/css/layout/layout-utilities.css';
import './../../../contrib/localgov_base/css/components/form-items.css';
import './../../../contrib/localgov_base/css/components/messages.css';
//
// // CSS imports from dhsc_theme - styles
import './../src/localgov/base/variables.css';
import './../src/localgov/base/global.css';
import './../src/localgov/layout/layout-utilities.css';
//
// import '../src/overrides/scss/overrides.scss';

import '../dist/css/localgov.css';
import '../dist/css/custom.css';
import '../dist/css/overrides.css';


/** @type { import('@storybook/react').Preview } */
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
