import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import footerTopTwig from "./footer-top.twig";
import footerTwig from "./footer.twig";
import footerBottomTwig from "./footer-bottom.twig";
import footerLegalTwig from "./footer-legal.twig";
import './footer.scss';
import DHSCLogo from '../../../assets/DHSC.svg';
import CareLogo from '../../../assets/logo.svg';

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

const FooterTemplate = ({ attributes, title, content, image, text }) =>
  footerTwig({
    attributes,
    title,
    content,
    image,
    text
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


const MenuFooterTemplate = (args) => MenuFooterTwig({
  ...MenuFooter.args
});

export const FooterTop = FooterTopTemplate.bind({});
FooterTop.args = {
  attributes: new DrupalAttributes(),
  title: 'Footer top',
  content: 'Footer top'
};

export const Footer = FooterTemplate.bind({});
Footer.args = {
  attributes: new DrupalAttributes(),
  title: 'Digitising Social Care',
  content: [ MenuFooterTemplate ],
  image: DHSCLogo,
  text: 'Copyright 2023 DHSC'
};

export const FooterBottom = FooterBottomTemplate.bind({});
FooterBottom.args = {
  attributes: new DrupalAttributes(),
  title: 'Footer Bottom',
  content: 'Footer Bottom'
};
export const FooterLegal = FooterLegalTemplate.bind({});
FooterLegal.args = {
  attributes: new DrupalAttributes(),
  title: 'Footer Legal',
  content: 'Footer Legal'
};
