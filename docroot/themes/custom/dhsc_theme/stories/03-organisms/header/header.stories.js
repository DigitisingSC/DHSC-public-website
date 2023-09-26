import DrupalAttributes from '../../../.storybook/drupalAttributes';
import React from 'react';
import header from './header.twig';
import { BrandingHeader } from '../../02-molecules/branding/branding.stories';
import BrandingTwig from '../../02-molecules/branding/branding--header.twig';

import { ButtonSearch } from '../../01-atoms/button/button.stories';
import SearchButtonTwig from '../../01-atoms/button/button.twig';

export default {
  title: "Design System/Organisms/Header",
};

const Template = ({ attributes, content }) =>
  header({
    attributes,
    content
  });

const BrandingHeaderTemplate = (args) => BrandingTwig({
  ...BrandingHeader.args
});

const SearchButtonTemplate = (args) => SearchButtonTwig({
  ...ButtonSearch.args
});

export const Header = Template.bind({});
Header.args = {
  attributes: new DrupalAttributes(),
  content: [BrandingHeaderTemplate, SearchButtonTemplate]
}
