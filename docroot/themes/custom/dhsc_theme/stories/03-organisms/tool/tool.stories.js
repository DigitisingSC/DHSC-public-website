import DrupalAttributes from '../../../.storybook/drupalAttributes';
import React from 'react';
import toolAssessmentTwig from './tool--assessment.twig';
import toolSolutionsTwig from './tool--solutions.twig';
import './tool.scss';

export default {
  title: "Design System/Organisms/Tool",
};

const ToolAssessmentTemplate = ({ attributes, title, variant, summary, no_result, result, submission_url }) =>
  toolAssessmentTwig({
    attributes,
    summary,
    title,
    variant,
    no_result,
    result,
    submission_url
  });

const ToolSolutionsTemplate = ({ attributes, title, count, total_count }) =>
  toolSolutionsTwig({
    attributes,
    title,
    count,
    total_count,
  });

export const toolAssessment = ToolAssessmentTemplate.bind({});

toolAssessment.args = {
  attributes: new DrupalAttributes(),
  title: 'Assessment',
  variant: '',
  summary: 'summary',
  no_result: '',
  result: '12 results',
  submission_url: 'https://google.co.uk'
}

export const toolSolutions = ToolSolutionsTemplate.bind({});

toolSolutions.args = {
  attributes: new DrupalAttributes(),
  title: 'Solutions',
  variant: 'solutions',
}
