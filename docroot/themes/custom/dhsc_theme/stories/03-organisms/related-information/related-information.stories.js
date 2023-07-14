import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import relatedInformation from "./related-information.twig";

export default {
  title: "Design System/Organisms/Related information",
};

const Template = ({ attributes, title, items, read_more_link }) => relatedInformation({
  attributes,
  title,
  items,
  read_more_link,
});

export const RelatedInformation = Template.bind({});
RelatedInformation.args = {
  attributes: new DrupalAttributes(),
  title: 'Related information',
  items: [
    { title: 'Item 1', url: '#' , subtitle: '7 June 2023 - Online'},
    { title: 'Item 2 - This one whit a much longer title to test how lines break', url: '#2', subtitle: '7 June 2023' },
    { title: 'Item 3', url: '#3' },
  ],
  read_more_link: `<a href="#">See all events</a>`
}
