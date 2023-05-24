import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import breadcrumbs from "./breadcrumbs.twig";
import './breadcrumbs.scss';
export default {
  title: "Design System/Molecules/Breadcrumbs",
};

const Template = ({ attributes, items }) =>
  breadcrumbs({
    attributes,
    items,
  });

export const Breadcrumbs = Template.bind({});
Breadcrumbs.args = {
  attributes: new DrupalAttributes(),
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
