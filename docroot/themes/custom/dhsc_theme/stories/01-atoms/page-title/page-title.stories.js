import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import pageTitleTwig from "./page-title.twig";

export default {
  title: "Design System/Atoms/Page title",
};

const Template = ({ attributes, titlePrefix, content, titleSuffix, }) =>
  pageTitleTwig({
    attributes,
    titlePrefix,
    content,
    titleSuffix,
  });

export const PageTitle = Template.bind({});
PageTitle.args = {
  attributes: new DrupalAttributes(),
  titlePrefix: '',
  content: '<h1>Page title</h1>',
  titleSuffix: '',
};
