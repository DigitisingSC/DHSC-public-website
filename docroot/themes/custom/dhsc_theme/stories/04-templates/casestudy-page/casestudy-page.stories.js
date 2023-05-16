import React from 'react';
import DrupalAttribute from '../../../.storybook/drupalAttributes';
import caseStudyPage from "./casestudy-page.twig";

export default {
  title: "Design System/Templates/Case study page",
};

const Template = ({ content }) =>
  caseStudyPage({
    content
  });

export const CaseStudyPage= Template.bind({});
CaseStudyPage.args = {
  content: "Case Study page.."
};
