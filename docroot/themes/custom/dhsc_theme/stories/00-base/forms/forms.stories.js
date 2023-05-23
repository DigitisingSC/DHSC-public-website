import React from 'react';
import DrupalAttribute from '../../../.storybook/drupalAttributes';
import forms from "./forms.twig";
import "./forms.scss";

export default {
  title: "Design System/Base/Forms",
};

const Template = () => forms();

export const Forms = Template.bind({});
