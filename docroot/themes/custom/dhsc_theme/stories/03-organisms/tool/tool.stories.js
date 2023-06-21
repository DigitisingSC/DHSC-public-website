import DrupalAttributes from '../../../.storybook/drupalAttributes';
import React from 'react';
import toolAssessmentTwig from './tool--assessment.twig';
import toolSolutionsTwig from './tool--solutions.twig';
import './tool.scss';

import emailFormTwig from './emailForm.twig';

import { toolResult } from "../../02-molecules/tool-result/tool-result.stories.js";
import toolResultTwig from "../../02-molecules/tool-result/tool-result.twig";

import { SupplierTeaser } from "../../02-molecules/teaser/teaser.stories.js";
import supplierTeaserTwig from "../../02-molecules/teaser/teaser--supplier.twig";

import { toolSearchCriteria } from '../../02-molecules/tool-search-criteria/tool-search-criteria.stories.js';
import toolSearchCriteriaTwig from '../../02-molecules/tool-search-criteria/tool-search-criteria.twig';

import { svgIcon } from '../../01-atoms/svg/svg.stories';
import svgIconTwig from '../../01-atoms/svg/svg.twig';

export default {
  title: "Design System/Organisms/Tools",
};

const toolResultTemplate = (args) => toolResultTwig({
  ...toolResult.args,
});

const supplierTeaserTemplate1 = (args) => supplierTeaserTwig({
  ...SupplierTeaser.args,
});

const supplierTeaserTemplate2 = (args) => supplierTeaserTwig({
  ...SupplierTeaser.args,
});

const supplierTeaserTemplate3 = (args) => supplierTeaserTwig({
  ...SupplierTeaser.args,
});

const toolSearchCriteriaTemplate = (args) => toolSearchCriteriaTwig({
  ...toolSearchCriteria.args,
})

const svgIconTemplate = (args) => svgIconTwig({
  ...svgIcon.args,
  icon: 'triangle',
});

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

const ToolSolutionsTemplate = ({
  attributes,
  title,
  icon,
  count,
  total_count,
  no_result,
  search_criteria,
  submission_url,
  result,
  non_matching_count,
  partial_matches,
  no_matches,
  email_form
}) =>
  toolSolutionsTwig({
    attributes,
    icon,
    title,
    count,
    total_count,
    no_result,
    search_criteria,
    submission_url,
    result,
    non_matching_count,
    partial_matches,
    no_matches,
    email_form
  });

export const toolAssessment = ToolAssessmentTemplate.bind({});

toolAssessment.args = {
  attributes: new DrupalAttributes(),
  title: 'Assessment',
  variant: '',
  summary: 'summary',
  no_result: '',
  result: { toolResultTemplate },
  submission_url: 'https://google.co.uk'
}

export const toolSolutions = ToolSolutionsTemplate.bind({});

toolSolutions.args = {
  attributes: new DrupalAttributes(),
  title: 'Solutions',
  variant: 'solutions',
  icon: svgIconTemplate,
  count: '5',
  total_count: '12',
  no_result: '',
  search_criteria: toolSearchCriteriaTemplate,
  submission_url: '',
  result: { supplierTeaserTemplate1, supplierTeaserTemplate2, supplierTeaserTemplate3 },
  non_matching_count: '0',
  partial_matches: '1',
  no_matches: '1',
  email_form: emailFormTwig
}