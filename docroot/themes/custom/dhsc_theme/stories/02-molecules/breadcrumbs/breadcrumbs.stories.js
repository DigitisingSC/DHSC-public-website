import React from 'react';
import DrupalAttribute from '../../../.storybook/drupalAttributes';
import breadcrumbs from "./breadcrumbs.twig";
import './breadcrumbs.scss';
export default {
  title: "Design System/Molecules/Breadcrumbs",
};

const Template = ({ items }) =>
  breadcrumbs({
    items,
  });

export const Breadcrumbs = Template.bind({});
Breadcrumbs.args = {
  items: [
    {
      text: 'Home',
      url: '#',
    },
    {
      text: 'Grandparent',
      url: '#',
    },
    {
      text: 'Parent',
      url: '#',
    },
  ]
};
