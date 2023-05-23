import React from 'react';
import DrupalAttribute from '../../../.storybook/drupalAttributes';
import caseStudyPage from "./casestudy-page.twig";

export default {
  title: "Design System/Templates/Case study page",
};

const Template = ({ title, content }) =>
  caseStudyPage({
    title,
    content
  });

export const CaseStudyPage= Template.bind({});
CaseStudyPage.args = {
  title: "Case study",
  content: "Case study content.."
};
