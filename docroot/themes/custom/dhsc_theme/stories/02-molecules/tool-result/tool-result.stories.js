import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import './tool-result.scss';
import toolResultTwig from "./tool-result.twig";

export default {
  title: "Design System/Molecules/Tool Result",
};

const Template = ({ attributes, title, url, answer, content }) =>
  toolResultTwig({
    attributes,
    title,
    url,
    answer,
    content
  });

export const toolResult = Template.bind({});
toolResult.args = {
  attributes: new DrupalAttributes(),
  title: "Does your organisation have access to a reliable, fast connection to the internet?",
  url: "https://www.digitalsocialcare.co.uk",
  answer: 'Yes',
  content: "<p>Great. This is the first step to unlocking many other digital ways of working in your organisation.&nbsp;</p> <p>If you're already connected but are limited by poor speed and reliability, you could upgrade your connection.</p> <p><a href='#'>Go here for a step-by-step guide to upgrade your internet connection.</a></p>"
};
