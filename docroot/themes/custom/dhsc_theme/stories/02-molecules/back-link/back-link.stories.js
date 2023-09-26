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

const Template = ({ attributes, label, icon, link, variant }) =>
  backLinkTwig({
   attributes,
   label,
   icon,
   link,
   variant
  });

export const backLink = Template.bind({});
backLink.args = {
  attributes: new DrupalAttributes(),
  label: 'Back',
  icon: svgBackLinkTemplate,
  link: '#',
  variant: 'default'
};
