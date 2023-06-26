import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import copyrightTwig from "./copyright.twig";

export default {
  title: "Design System/Molecules/Legal",
};

const Template = ({ attributes, text }) => copyrightTwig({
  attributes,
  text
});

export const legalCopyright = Template.bind({});
legalCopyright.args = {
  attributes: new DrupalAttributes(),
  text: 'Copyright text'
};
