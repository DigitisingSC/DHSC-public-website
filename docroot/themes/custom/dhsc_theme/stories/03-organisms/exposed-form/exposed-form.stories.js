import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import exposedForm from "./exposed-form.twig";
import './exposed-form.scss';
import '../../00-base/forms/forms.scss';

export default {
  title: "Design System/Organisms/Exposed Form",
};

const Template = ({ title, attributes, children }) => exposedForm({
  title,
  attributes,
  children,
});

export const form = Template.bind({});
form.args = {
  title: 'Filters',
  attributes: new DrupalAttributes(),
  children: `<label for="fname">First name:</label><br>
    <input type="text" id="fname" name="fname" value="John"><br>
    <label for="lname">Last name:</label><br>
    <input type="text" id="lname" name="lname" value="Doe"><br><br>
    <input type="submit" value="Submit" class="form-submit">`,
};
