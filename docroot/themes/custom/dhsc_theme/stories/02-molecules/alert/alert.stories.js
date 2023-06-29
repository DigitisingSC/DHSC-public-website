import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import alert from './alert.twig';
import './alert.scss';

export default {
  title: "Design System/Molecules/Alert",
  argTypes: {
    text: { control: 'text' },
  },
};

const Template = ({ attributes, text, is_dismissible, ...args }) =>
    alert({
      attributes,
      text,
      is_dismissible,
      ...args,
    });

export const Alert = Template.bind({});
Alert.args = {
  attributes: new DrupalAttributes(),
  alertType: 'price',
  text: '<p>An example alert with a <a href="#">link</a></p>',
  is_dismissible: true,
};
