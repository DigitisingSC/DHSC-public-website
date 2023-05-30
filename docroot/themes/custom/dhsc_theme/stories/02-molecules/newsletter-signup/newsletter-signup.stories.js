import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import form from "./_form.twig";
import newsletterSignupTwig from "./newsletter-signup.twig";
import './newsletter-signup.scss';

export default {
  title: "Design System/Molecules/Newsletter Signup",
};

const Template = ({ attributes, title, description, form }) =>
  newsletterSignupTwig({
    attributes,
    title,
    description,
    form
  });

const formTemplate = () => form();

export const newsletterSignup = Template.bind({});
newsletterSignup.args = {
  attributes: new DrupalAttributes(),
  title: "Sign up to our newsletter",
  description: "Lorem ipsum dolor sit amet",
  form: formTemplate
};
