import React from 'react';
import DrupalAttribute from '../../../.storybook/drupalAttributes';
import button from "./button.twig";

export default {
  title: "Design System/Atoms/Button",
};

const Template = ({ variant, title, attributes, tag_name, children }) =>
  button({
    variant,
    title,
    attributes,
    tag_name,
    children,
  });

export const ButtonPrimary = Template.bind({});
ButtonPrimary.args = {
  title: 'Button primary',
  variant: 'primary',
  attributes: new DrupalAttribute(),
  tag_name: 'button',
  children: 'Button Primary',
};
//
export const ButtonSecondary = Template.bind({});
ButtonSecondary.args = {
  title: 'Button secondary',
  variant: 'secondary',
  attributes: new DrupalAttribute(),
  tag_name: 'button',
  children: 'Button Secondary',
};
//
