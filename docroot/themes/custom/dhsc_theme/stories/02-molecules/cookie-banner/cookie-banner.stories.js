import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import cookieBanner from "./cookie-banner.twig";
import './cookie-banner.scss';

export default {
  title: "Design System/Molecules/Cookie Banner",
};


const Template = ({...args}) => cookieBanner({...args});

export const CookieBanner = Template.bind({});
CookieBanner.args = {
  agree_button: "Accept",
  attributes: 'class="eu-cookie-compliance-banner eu-cookie-compliance-banner-info eu-cookie-compliance-banner--categories" '
   + 'aria-hidden="false"',
  tertiary_button_class: 'eu-cookie-compliance-reject-button',
  secondary_button_label: 'No, thanks',
  message: '' +
    '<p><strong>We use cookies on this site to enhance your experience.</strong></p>' +
    '<p>By clicking the accept button, you agree to us doing so.<a href="#">More info</a></p>',
};
