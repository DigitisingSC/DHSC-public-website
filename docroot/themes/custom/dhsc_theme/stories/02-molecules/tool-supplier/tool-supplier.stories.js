import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import './tool-supplier.scss';
import toolSupplierTwig from "./tool-supplier.twig";

export default {
  title: "Design System/Molecules/Tool Supplier",
};

const Template = ({ attributes, title, url, answer, content }) =>
  toolSupplierTwig({
    attributes,
    variant,
    title,
    url,
    answer,
    content
  });

export const toolSupplier = Template.bind({});
toolSupplier.args = {
  attributes: new DrupalAttributes(),
  variant: 'match',
  title: "Microsoft",
  url: "https://www.digitalsocialcare.co.uk",
  content: "<p>Microsoft corp.</p>"
};
