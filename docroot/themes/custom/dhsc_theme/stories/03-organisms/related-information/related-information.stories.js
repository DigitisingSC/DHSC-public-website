import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import relatedInformation from "./related-information.twig";

export default {
  title: "Design System/Organisms/Related information",
};

const Template = ({ attributes, title, items }) => relatedInformation({
  attributes,
  title,
  items,
});

export const RelatedInformation = Template.bind({});
RelatedInformation.args = {
  attributes: new DrupalAttributes(),
  title: 'Related information',
  items: [
    { title: 'Item 1', url: '#' },
    { title: 'Item 2 - This one whit a much longer title to test how lines break', url: '#2' },
    { title: 'Item 3', url: '#3' },
  ],
}
