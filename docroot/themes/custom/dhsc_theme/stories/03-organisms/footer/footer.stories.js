import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import footerTopTwig from "./footer-top.twig";
import footerTwig from "./footer.twig";
import footerBottomTwig from "./footer-bottom.twig";
import footerLegalTwig from "./footer-legal.twig";
import { FooterContact } from '../../02-molecules/footer-contact/footer-contact.stories';
import footerContactTwig from '../../02-molecules/footer-contact/footer-contact.twig';
import { legalCopyright } from '../../02-molecules/legal/copyright.stories';
import legalCopyrightTwig from '../../02-molecules/legal/copyright.twig';
import { BrandingFooter } from '../../02-molecules/branding/branding.stories';
import footerBrandingTwig from '../../02-molecules/branding/branding--footer.twig';

import DHSCLogo from '../../assets/DHSC.svg';

import './footer.scss';

import { MenuFooter } from "../../02-molecules/menus/menu-footer.stories";
import MenuFooterTwig from "../../02-molecules/menus/menu-footer.twig";

export default {
  title: "Design System/Organisms/Footer",
};

const FooterTopTemplate = ({ attributes, title, content }) =>
  footerTopTwig({
    attributes,
    title,
    content,
  });

const FooterTemplate = ({ attributes, title, content, logo }) =>
  footerTwig({
    attributes,
    title,
    content,
    logo,
  });

const FooterBottomTemplate = ({ attributes, title, content }) =>
  footerBottomTwig({
    attributes,
    title,
    content,
  });

const FooterLegalTemplate = ({ attributes, title, content }) =>
  footerLegalTwig({
    attributes,
    title,
    content,
  });

const legalCopyrightTemplate = () => legalCopyrightTwig({
  ...legalCopyright.args
});

const footerBrandingTemplate = () => footerBrandingTwig({
  ...BrandingFooter.args
});

const footerContactTemplate = () => footerContactTwig({
  ...FooterContact.args
});


const menuFooterTemplate = () => MenuFooterTwig({
  ...MenuFooter.args
});

export const FooterTop = FooterTopTemplate.bind({});
FooterTop.args = {
  attributes: new DrupalAttributes(),
  content: [ footerBrandingTemplate ]
};

export const Footer = FooterTemplate.bind({});
Footer.args = {
  attributes: new DrupalAttributes(),
  content: [ footerContactTemplate(), menuFooterTemplate ],
  logo: DHSCLogo
};

export const FooterBottom = FooterBottomTemplate.bind({});
FooterBottom.args = {
  attributes: new DrupalAttributes(),
  content: 'Footer Bottom'
};

export const FooterLegal = FooterLegalTemplate.bind({});
FooterLegal.args = {
  attributes: new DrupalAttributes(),
  content: [ legalCopyrightTemplate ]
};
