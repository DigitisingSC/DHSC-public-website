import React from 'react';
import DrupalAttribute from '../../../.storybook/drupalAttributes';
import menuFooter from "./menu-footer.twig";

export default {
  title: "Design System/Molecules/Menu Footer",
};

const Template = () => menuFooter();

export const MenuFooter = Template.bind({});
