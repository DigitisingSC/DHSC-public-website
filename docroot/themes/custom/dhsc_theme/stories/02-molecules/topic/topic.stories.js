import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import topicTwig from "./topic.twig";

export default {
  title: "Design System/Molecules/Topic",
};

const Template = ({ link, heading, description, attributes }) =>
  topicTwig({
    link,
    heading,
    description,
    attributes
  });

export const Topic = Template.bind({});
Topic.args = {
  link: "https://www.digitalsocialcare.co.uk/",
  heading: "Get help using technology at your organisation",
  description: "Learn how to get connected, use secure email, use mobile devices and get digital social care records",
  attributes: new DrupalAttributes()
};
