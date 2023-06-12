import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import searchFormTwig from "./search-form.twig";
import './search-form.scss';
import { svgIcon } from '../../01-atoms/svg/svg.stories';
import svgIconTwig from '../../01-atoms/svg/svg.twig';

export default {
  title: "Design System/Organisms/Search Form",
};

const Template = ({ attributes, title, items, icon }) =>
  searchFormTwig({
    attributes,
    title,
    items,
    icon
  });
const searchIconTemplate = (args) => svgIconTwig({
  ...svgIcon.args,
  icon: 'search',
});

export const SearchForm = Template.bind({});
SearchForm.args = {
  attributes: new DrupalAttributes(),
  title: 'Search',
  items: [
    { title: 'Item 1', url: '#' },
    { title: 'Item 2', url: '#' },
    { title: 'Item 3', url: '#' },
    { title: 'Item 4', url: '#' },
    { title: 'Item 5', url: '#' },
  ],
  icon: searchIconTemplate,
}
