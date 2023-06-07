import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import searchRegionTwig from "./search-region.twig";

export default {
  title: "Design System/Organisms/Search Region",
};

const Template = ({ attributes, content }) =>
  searchRegionTwig({
    attributes,
    content
  });

export const SearchRegion = Template.bind({});
SearchRegion.args = {
  attributes: new DrupalAttributes(),
  content: 'Search'
}
