import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import searchRegionTwig from "./search-region.twig";
import { SearchForm } from '../search-form/search-form.stories';
import SearchFormTwig from '../search-form/search-form.twig';
import navigationPrimary from '../navigation/navigation--primary.twig';

export default {
  title: "Design System/Organisms/Search Region",
};

const Template = ({ attributes, content }) =>
  searchRegionTwig({
    attributes,
    content
  });
const SearchFormTemplate = (args) =>
  SearchFormTwig({
    ...SearchForm.args
  });

export const SearchRegion = Template.bind({});
SearchRegion.args = {
  attributes: new DrupalAttributes(),
  content: SearchFormTemplate
}
