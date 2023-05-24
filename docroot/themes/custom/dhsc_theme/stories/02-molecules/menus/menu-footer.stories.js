import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import menuFooter from "./menu-footer.twig";

export default {
  title: "Design System/Molecules/Footer Menu",
};

const Template = () => menuFooter();

export const MenuFooter = Template.bind({});
