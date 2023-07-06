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

const toolSupplierNoMatchTemplate = ({ attributes, variant, title, url, section, answers }) =>
  toolSupplierNoMatchTwig({
    attributes,
    variant,
    title,
    url,
    section,
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
  section: "Care services",
  answers: ['Extra care services', 'Domiciliary care services']
};
