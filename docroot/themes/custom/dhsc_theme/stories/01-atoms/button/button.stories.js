import React from 'react';
import DrupalAttribute from '../../../.storybook/drupalAttributes';
import button from "./button.twig";

export default {
  title: "Design System/Atoms/Button",
};

const Template = ({ title, attributes, tag_name, variant, children }) =>
  button({
    title,
    attributes,
    tag_name,
    variant,
    children,
  });

export const Button = Template.bind({});
Button.args = {
  title: 'Button title',
  attributes: new DrupalAttribute(),
  tag_name: 'button',
  variant: 'primary',
  children: 'Button',
};
