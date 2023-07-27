import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import relatedInformation from "./further-information.twig";

export default {
  title: "Design System/Organisms/Further information",
};

const Template = ({ attributes, title, items }) => relatedInformation({
  attributes,
  title,
  items,
});

export const FurtherInformation = Template.bind({});
FurtherInformation.args = {
  attributes: new DrupalAttributes(),
  title: 'Further information',
  items: [
    `<a href="#">Referenced page title</a>`,
    `<a href="#">Item 2 - This one whit a much longer title to test how lines break</a>`,
    `<a href="#">Title</a>`,
  ],
}
