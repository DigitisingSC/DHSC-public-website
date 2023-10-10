import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import breadcrumbs from "./breadcrumbs.twig";
import './breadcrumbs.scss';
import { svgIcon } from '../../01-atoms/svg/svg.stories';
import svgIconTwig from '../../01-atoms/svg/svg.twig';

export default {
  title: "Design System/Molecules/Breadcrumbs",
};

const svgIconChevronTemplate = (args) => svgIconTwig({
  ...svgIcon.args,
  icon: 'chevron-right',
});

const Template = ({ attributes, icon, items }) =>
  breadcrumbs({
    attributes,
    icon,
    items,
  });

export const Breadcrumbs = Template.bind({});
Breadcrumbs.args = {
  attributes: new DrupalAttributes(),
  icon: svgIconChevronTemplate,
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
    {
      text: 'Child',
      url: '#',
    },
    {
      text: 'Grandchild',
      url: '#',
    },
    {
      text: 'Current',
    },
  ]
};
