import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import detailsTwig from "./form-details.twig";
import './form-details.scss';
import '../../00-base/forms/forms.scss';

export default {
  title: "Design System/Molecules/Form Details",
};

const Template = ({ attributes, summary_attributes, title, errors, description, children, value }) => detailsTwig({
  attributes,
  summary_attributes,
  title,
  errors,
  description,
  children,
  value,
});

export const details = Template.bind({});
details.args = {
  attributes: new DrupalAttributes(),
  summary_attributes: new DrupalAttributes(),
  title: 'Details title',
  errors: '',
  children: `<div class="a-form-item--checkbox">
  <input type="checkbox" id="option-1" name="option-1" checked>
  <label for="option-1">Option 1</label>
  </div>
  <div class="a-form-item--checkbox">
  <input type="checkbox" id="option-2" name="option-2">
  <label for="option-2">Option 2</label>
  </div>`,
  value: ''
};


