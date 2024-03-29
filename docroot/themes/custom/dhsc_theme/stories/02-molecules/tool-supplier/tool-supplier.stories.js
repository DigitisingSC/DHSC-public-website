import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import toolSupplierMatchTwig from "./tool-supplier--match.twig";
import toolSupplierNoMatchTwig from "./tool-supplier--nomatch.twig";
import { svgIcon } from '../../01-atoms/svg/svg.stories';
import svgIconTwig from '../../01-atoms/svg/svg.twig';

import './tool-supplier.scss';

export default {
  title: "Design System/Molecules/Tool Supplier",
};

const svgIconCloseTemplate = (args) => svgIconTwig({
  ...svgIcon.args,
  icon: 'close',
});

const toolSupplierMatchTemplate = ({ attributes, variant, title, url, content }) =>
  toolSupplierMatchTwig({
    attributes,
    variant,
    title,
    url,
    content
  });

const toolSupplierNoMatchTemplate = ({ attributes, variant, title, url, icon_close, answers, preview_component }) =>
  toolSupplierNoMatchTwig({
    attributes,
    variant,
    title,
    url,
    icon_close,
    answers,
    preview_component
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
  icon_close: svgIconCloseTemplate,
  preview_component: true,
  answers: [
    {
      section: 'Section 1',
      answer: [
        'answer 1', 'answer 2', 'answer 3'
      ]
    },
    {
      section: 'Section 2',
      answer: [
        'answer 1', 'answer 2', 'answer 3'
      ]
    },
  ]
};
