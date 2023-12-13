import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import './topic.scss';
import topicTwig from "./topic.twig";
import { svgIcon } from '../../01-atoms/svg/svg.stories';
import svgIconTwig from '../../01-atoms/svg/svg.twig';

export default {
  title: "Design System/Molecules/Topic",
};

const svgIconTemplate = (args) => svgIconTwig({
  ...svgIcon.args,
  icon: 'card-arrow',
});

const Template = ({ link, heading, description, highlight, attributes, icon }) =>
  topicTwig({
    link,
    heading,
    description,
    highlight,
    attributes,
    icon
  });


export const Topic = Template.bind({});
Topic.args = {
  link: "https://www.digitalsocialcare.co.uk/",
  heading: "Get help using technology at your organisation",
  description: "Learn how to get connected, use secure email, use mobile devices and get digital social care records",
  highlight: 1,
  attributes: new DrupalAttributes(),
  icon: svgIconTemplate,
};
