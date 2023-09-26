import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import menuFooter from "./menu-footer.twig";
import './menu-footer.scss';

export default {
  title: "Design System/Molecules/Footer Menu",
};

const Template = ({ attributes, title, items }) => menuFooter({ attributes, title, items });

export const MenuFooter = Template.bind({});
MenuFooter.args = {
  attributes: new DrupalAttributes(),
  title: '',
  items: [
    { title: 'Item 1', url: '#' },
    { title: 'Item 2', url: '#' },
    { title: 'Item 3', url: '#' },
  ]
};
