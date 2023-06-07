import DrupalAttributes from '../../../.storybook/drupalAttributes';
import React from 'react';
import header from './header.twig';
import { Branding } from '../../02-molecules/branding/branding.stories';
import BrandingTwig from '../../02-molecules/branding/branding.twig';

import { ButtonSearch } from '../../01-atoms/button/button.stories';
import SearchButtonTwig from '../../01-atoms/button/button.twig';

export default {
  title: "Design System/Organisms/Header",
  content: Branding
};

const Template = ({ attributes, content }) =>
  header({
    attributes,
    content
  });

const BrandTemplate = (args) => BrandingTwig({
  ...Branding.args
});

const SearchButtonTemplate = (args) => SearchButtonTwig({
  ...ButtonSearch.args
});

export const Header = Template.bind({});
Header.args = {
  attributes: new DrupalAttributes(),
  content: [BrandTemplate, SearchButtonTemplate]
}
