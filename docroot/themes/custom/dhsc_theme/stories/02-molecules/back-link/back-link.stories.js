import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import backLinkTwig from "./back-link.twig";
import { svgIcon } from '../../01-atoms/svg/svg.stories';
import svgIconTwig from '../../01-atoms/svg/svg.twig';
import './back-link.scss';

export default {
  title: "Design System/Molecules/Back Link",
};

const svgBackLinkTemplate = (args) => svgIconTwig({
  ...svgIcon.args,
  icon: 'chevron-left',
});

const Template = ({ attributes, label, icon, text, link, variant }) =>
  backLinkTwig({
   attributes,
   label,
   icon,
   text,
   link,
   variant
  });

export const backLink = Template.bind({});
backLink.args = {
  attributes: new DrupalAttributes(),
  label: 'Back',
  icon: svgBackLinkTemplate,
};

export const backLinkForm = Template.bind({});
backLinkForm.args = {
  attributes: new DrupalAttributes(),
  label: 'Back',
  icon: svgBackLinkTemplate,
  link: '#',
  variant: 'form'
};

export const backLinkWithText = Template.bind({});
backLinkWithText.args = {
  attributes: new DrupalAttributes(),
  text: 'Get help with technology',
  link: '/get-help-set-and-use-technology',
  variant: 'text'
};
