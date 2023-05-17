import React from 'react';
import DrupalAttribute from '../../../.storybook/drupalAttributes';
import svg from "./svg.twig";

export default {
  title: "Design System/Atoms/Svg",
};

const Template = ({ icon }) =>
  svg({
    icon,
  });

export const svgIcon = Template.bind({});
svgIcon.args = {
  icon: "file",
};
