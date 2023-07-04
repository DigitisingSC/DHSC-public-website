import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import FullContentListingTwig from "./full-content-listings.twig";
import './full-content-listings.scss';

import { ContentGrid } from '../content-grid/content-grid.stories';
import ContentGridTwig from '../content-grid/content-grid.twig';
export default {
  title: "Design System/Organisms/Full content listings",
};

const ContentGridTemplate = (args) =>
  ContentGridTwig({
    ...ContentGrid.args
  });

const Template = ({ attributes, title, content }) =>
  FullContentListingTwig({
    attributes,
    title,
    content,
  });

export const FullContentListing = FullContentListingTwig.bind({});
FullContentListing.args = {
  attributes: new DrupalAttributes(),
  title: 'Full content listings',
  content: { ContentGridTemplate }
};
