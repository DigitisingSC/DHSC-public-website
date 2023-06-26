import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import './topic.scss';
import topicTwig from "./topic.twig";
import { svgIcon } from '../../01-atoms/svg/svg.stories';

export default {
  title: "Design System/Molecules/Topic",
};

const svgIconTemplate = (args) => svgIcon({
  ...svgIcon.args
});

const Template = ({ link, heading, description, attributes, icon }) =>
  topicTwig({
    link,
    heading,
    description,
    attributes,
    icon
  });


export const Topic = Template.bind({});
Topic.args = {
  link: "https://www.digitalsocialcare.co.uk/",
  heading: "Get help using technology at your organisation",
  description: "Learn how to get connected, use secure email, use mobile devices and get digital social care records",
  attributes: new DrupalAttributes(),
  icon: svgIconTemplate,
};
