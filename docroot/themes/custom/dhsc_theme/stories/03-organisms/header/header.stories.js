import DrupalAttribute from '../../../.storybook/drupalAttributes';
import React from 'react';
import header from "./header.twig";
import { Branding } from "../../02-molecules/branding/branding.stories";
import BrandingTwig from "../../02-molecules/branding/branding.twig";
export default {
  title: "Design System/Organisms/Header",
  content: Branding
};

const Template = ({ content }) =>
  header({
    content
  });

const BrandTemplate = (args) => BrandingTwig({
  ...Branding.args
});

export const Header = Template.bind({});
Header.args = {
  content: BrandTemplate
}
