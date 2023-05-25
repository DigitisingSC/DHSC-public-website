import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import dynamicContentListingTwig from "./dynamic-content-listing.twig";
import './dynamic-content-listing.scss';

import { teaserCollection } from '../teaser-collection/teaser-collection.stories';
import teaserCollectionTwig from '../teaser-collection/teaser-collection.twig';

export default {
  title: "Design System/Organisms/Dynamic Content Listing",
};

const Template = ({ attributes, title, items, link }) =>
  dynamicContentListingTwig({
    attributes,
    title,
    items
  });

const teaserCollectionTemplate1 = (args) => teaserCollectionTwig({
  ...teaserCollection.args,
});

const teaserCollectionTemplate2 = (args) => teaserCollectionTwig({
  ...teaserCollection.args,
});

export const dynamicContentListing = Template.bind({});

dynamicContentListing.args = {
  attributes: new DrupalAttributes(),
  title: 'Dynamic content listing',
  items: { teaserCollectionTemplate1, teaserCollectionTemplate2 },
}
