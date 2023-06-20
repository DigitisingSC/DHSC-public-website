import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import './tool-search-criteria.scss';
import toolResearchCriteriaTwig from "./tool-search-criteria.twig";
export default {
  title: "Design System/Molecules/Tool Search Criteria",
};

const answerA = "Care home services, including nursing";
const answerB = "Extra care services";
const answerC = "Shared lives services";
const Template = ({ attributes, section, icon, answers }) =>
  toolResearchCriteriaTwig({
    attributes,
    section,
    answers,
  });

export const toolSearchCriteria = Template.bind({});
toolSearchCriteria.args = {
  attributes: new DrupalAttributes(),
  section: "Additional features",
  answers: { answerA, answerB, answerC },
};
