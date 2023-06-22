import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import toolSupplierMatchTwig from "./tool-supplier--match.twig";
import toolSupplierNoMatchTwig from "./tool-supplier--nomatch.twig";

export default {
  title: "Design System/Molecules/Tool Supplier",
};

const toolSupplierMatchTemplate = ({ attributes, variant, title, url, content }) =>
  toolSupplierMatchTwig({
    attributes,
    variant,
    title,
    url,
    content
  });

const toolSupplierNoMatchTemplate = ({ attributes, variant, title, url, answers }) =>
  toolSupplierNoMatchTwig({
    attributes,
    variant,
    title,
    url,
    answers,
  });

export const toolSupplierMatch = toolSupplierMatchTemplate.bind({});
toolSupplierMatch.args = {
  attributes: new DrupalAttributes(),
  variant: 'match',
  title: "Microsoft",
  url: "https://www.digitalsocialcare.co.uk",
  content: "<p>Microsoft corp.</p>",
};

export const toolSupplierNoMatch = toolSupplierNoMatchTemplate.bind({});
toolSupplierNoMatch.args = {
  attributes: new DrupalAttributes(),
  variant: 'nomatch',
  title: "Microsoft",
  url: "https://www.digitalsocialcare.co.uk",
  answers: [
    { 'section': 'Section 1', 'answer': 'answer 1' },
    { 'section': 'Section 2', 'answer': 'answer 2' }
  ]
};
