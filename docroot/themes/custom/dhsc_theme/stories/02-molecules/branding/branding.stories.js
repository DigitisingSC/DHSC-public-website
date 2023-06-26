import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import brandingHeaderTwig from './branding--header.twig';
import brandingFooterTwig from './branding--footer.twig';
import siteLogo from '../../assets/logo.svg';
import siteLogoInverted from '../../assets/logo-inverted.svg';

export default {
  title: "Design System/Molecules/Branding",
};

const BrandingHeaderTemplate = ({ attributes, variant, logo, site_name, site_slogan }) =>
  brandingHeaderTwig({
    attributes,
    variant,
    logo,
    site_name,
    site_slogan
  });

const BrandingFooterTemplate = ({ attributes, variant, logo, site_name, site_slogan }) =>
  brandingFooterTwig({
    attributes,
    variant,
    logo,
    site_name,
    site_slogan
  });

export const BrandingHeader = BrandingHeaderTemplate.bind({});
BrandingHeader.args = {
  attributes: new DrupalAttributes(),
  variant: 'header',
  logo: siteLogo,
  site_name: 'Digitising Social Care',
  site_slogan: 'Digitising Social Care'
};
export const BrandingFooter = BrandingFooterTemplate.bind({});
BrandingFooter.args = {
  attributes: new DrupalAttributes(),
  variant: 'footer',
  logo: siteLogoInverted,
  site_name: 'Digitising Social Care',
  site_slogan: 'Digitising Social Care'
};
