import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import searchForm from "./search-page-search-form.twig";
import './search-page-search-form.scss';
import '../../00-base/forms/forms.scss';

export default {
  title: "Design System/Organisms/Search page search Form",
};

const Template = ({ attributes, children }) => searchForm({
  attributes,
  children,
});

export const form = Template.bind({});
form.args = {
  attributes: new DrupalAttributes(),
  children: `<label for="fname">Search:</label><br>
    <div class="form-item-search">
      <input type="text" x-model="searchInputValue" id="search" class="form-text o-search-page-search-form__submit-button">
      <input x-show="searchInputValue !== ''" @click.prevent @click="searchInputValue = ''" class="o-search-page-search-form__reset-button button js-form-submit form-submit" type="submit" name="op" value="Reset">
    </div>
    <div class="form-actions">
      <input type="submit" value="Submit" class="form-submit">
    </div>`,
};
