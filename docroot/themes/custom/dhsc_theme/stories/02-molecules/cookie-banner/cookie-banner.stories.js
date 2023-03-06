import cookieBanner from "./cookie-banner.twig";

import './../../../../../custom/dhsc_theme/css/components/banner.css';
import './css/cookie-banner.scss';

import './js/cookie-banner'

export default {
  title: "Design System/Molecules/Cookie Banner",
};


const Template = ({
  attributes,
  popup_html_info,
  cookie_categories,
  cookie_categories_details,
  message,
  agree_button,
  save_preferences_button_label,
  tertiary_button_label,
  tertiary_button_class,
 }) =>
  cookieBanner({
    attributes,
    popup_html_info,
    cookie_categories,
    cookie_categories_details,
    message,
    agree_button,
    save_preferences_button_label,
    tertiary_button_label,
    tertiary_button_class,
  });

export const CookieBannerHTML = Template.bind({});
CookieBannerHTML.args = {
  agree_button: "Accept all cookies",
  attributes: 'class="eu-cookie-compliance-banner eu-cookie-compliance-banner-info eu-cookie-compliance-banner--categories" '
   + 'aria-hidden="false"',
  save_preferences_button_label: 'Save preferences',
  tertiary_button_label: 'Reject unnecessary cookies',
  tertiary_button_class: 'eu-cookie-compliance-reject-button',
  message: '<p>We use some essential cookies to make this service work. We’d also like to use analytics cookies so we can understand how you use the service and make improvements.<a href="#TBC-cookie-page">View Cookies</a></p>',
  cookie_categories: {
    strictly_necessary_cookies : {
      uuid: 'a8adb48c-1128-4ae9-9d3d-237a5453c48e',
      langcode: 'en',
      status: true,
      dependencies: {},
      id: 'strictly_necessary_cookies',
      label: 'Strictly necessary cookies - always active',
      description: 'These cookies are essential to make our site work, so we don’t give you the option to turn them off. They help keep our site secure and enable you to move around our website and use its features, such as accessing secure areas.',
      checkbox_default_state: 'required',
      weight: -9,
    },
    functional_cookies : {
      uuid: '1fdab60f-1b25-4d1c-a52c-60435ae79a35',
      langcode: 'en',
      status: true,
      dependencies: {},
      id: 'functional_cookies',
      label: 'Functional cookies',
      description: 'These cookies are used to recognise you when you return to our website. This enables us to personalise our content for you, and remember your preferences.',
      checkbox_default_state: 'unchecked',
      weight: -8,
    },
    performance_cookies : {
      uuid: '417e0a11-f3b8-48a1-b96c-9f550c1f3cd2',
      langcode: 'en',
      status: true,
      dependencies: {},
      id: 'performance_cookies',
      label: 'Performance cookies',
      description: 'These cookies collect information about how our website is used, for example, the number of visitors to parts of the site and if you get any error messages. This helps us to improve our site and its content.',
      checkbox_default_state: 'unchecked',
      weight: -7,
    },
    targeting_cookies : {
      uuid: '55fd64fa-1863-4c9b-afad-f0f919384476',
      langcode: 'en',
      status: true,
      dependencies: {},
      id: 'targeting_cookies',
      label: 'Targeting cookies',
      description: 'These cookies are designed to gather information from you on your device to display advertisements to you based on relevant topics that interest you.',
      checkbox_default_state: 'unchecked',
      weight: -6,
    },
  },
};
