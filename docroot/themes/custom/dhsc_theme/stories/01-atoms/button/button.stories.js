import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import button from "./button.twig";
import './button.scss';

import { svgIcon } from '../svg/svg.stories';
import svgIconTwig from '../svg/svg.twig';

export default {
  title: "Design System/Atoms/Button",
};

const Template = ({ variant, icon_open, icon_close, title, attributes, tag_name, children }) =>
  button({
    variant,
    icon_open,
    icon_close,
    title,
    attributes,
    tag_name,
    children,
  });

const svgIconOpenTemplate = (args) => svgIconTwig({
  ...svgIcon.args,
  icon: 'search',
});

const svgIconCloseTemplate = (args) => svgIconTwig({
  ...svgIcon.args,
  icon: 'close',
});

export const ButtonPrimary = Template.bind({});
ButtonPrimary.args = {
  title: 'Button primary',
  variant: 'primary',
  attributes: new DrupalAttributes(),
  tag_name: 'button',
  children: 'Button Primary',
};

export const ButtonSecondary = Template.bind({});
ButtonSecondary.args = {
  title: 'Button secondary',
  variant: 'secondary',
  attributes: new DrupalAttributes(),
  tag_name: 'button',
  children: 'Button Secondary',
};

export const ButtonSearch = Template.bind({});
ButtonSearch.args = {
  title: 'Button search',
  icon_open: svgIconOpenTemplate,
  icon_close: svgIconCloseTemplate,
  variant: 'search',
  attributes: new DrupalAttributes(),
  tag_name: 'button',
  children: 'Search',
};
