import React from 'react';
import DrupalAttribute from '../../../.storybook/drupalAttributes';
import branding from "./branding.twig";
import siteLogo from "../../assets/logo.svg";
export default {
  title: "Design System/Molecules/Branding",
};

const Logo = `<img src=${siteLogo} alt='Digital Social Care'/>`;
const Template = ({ site_logo, site_name, site_slogan }) =>
  branding({
    site_logo,
    site_name,
    site_slogan
  });

export const Branding = Template.bind({});
Branding.args = {
  site_logo: siteLogo,
  site_name: 'Digitising Social Care',
  site_slogan: 'Transforming Social Care'
};
