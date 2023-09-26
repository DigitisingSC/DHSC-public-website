import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import svg from "./svg.twig";
import sprite from './sprite.svg';
export default {
  title: "Design System/Atoms/Svg Icons",
};

const Template = ({ sprite, icon }) =>
  svg({
    sprite,
    icon,
  });

export const svgIcon = Template.bind({});
svgIcon.args = {
  sprite: sprite,
  icon: 'chevron-right',
};
