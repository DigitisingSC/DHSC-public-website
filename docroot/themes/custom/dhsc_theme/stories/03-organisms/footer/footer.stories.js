import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import footerFirst from "./footer-first.twig";
import footerSecond from "./footer-second.twig";
import footerThird from "./footer-third.twig";
import footer from "./footer.twig";
import footerLowerFirst from "./lower-footer-first.twig";
import footerLowerSecond from "./lower-footer-second.twig";
import footerLowerThird from "./lower-footer-third.twig";
import './footer.scss';
import Logo from '../../../assets/DHSC.svg';

import { MenuFooter } from "../../02-molecules/menus/menu-footer.stories";
import MenuFooterTwig from "../../02-molecules/menus/menu-footer.twig";

export default {
  title: "Design System/Organisms/Footer",
};

const Template = ({ attributes, title, content, image, text }) =>
  footer({
    attributes,
    title,
    content,
    image,
    text
  });

const MenuFooterTemplate = (args) => MenuFooterTwig({
  ...MenuFooter.args
});

export const FooterFirst = Template.bind({});
FooterFirst.args = {
  attributes: new DrupalAttributes(),
  content: "Footer first"
};
export const FooterSecond = Template.bind({});
FooterSecond.args = {
  attributes: new DrupalAttributes(),
  content: "Footer second"
};
export const FooterThird = Template.bind({});
FooterThird.args = {
  attributes: new DrupalAttributes(),
  content: "Footer third"
};
export const Footer = Template.bind({});
Footer.args = {
  attributes: new DrupalAttributes(),
  title: 'Digitising Social Care',
  content: [ MenuFooterTemplate, MenuFooterTemplate, MenuFooterTemplate ],
  image: Logo,
  text: 'Copyright 2023 DHSC'
};

export const LowerFooterFirst = Template.bind({});
LowerFooterFirst.args = {
  attributes: new DrupalAttributes(),
  content: "Lower Footer first"
};
export const LowerFooterSecond = Template.bind({});
LowerFooterSecond.args = {
  attributes: new DrupalAttributes(),
  content: "Lower Footer second"
};
export const LowerFooterThird = Template.bind({});
LowerFooterThird.args = {
  attributes: new DrupalAttributes(),
  content: "Lower Footer third"
};
