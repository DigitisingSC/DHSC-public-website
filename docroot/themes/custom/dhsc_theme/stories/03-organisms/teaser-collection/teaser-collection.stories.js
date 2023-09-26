import DrupalAttributes from '../../../.storybook/drupalAttributes';
import React from 'react';
import teaserCollectionTwig from "./teaser-collection.twig";
import './teaser-collection.scss';

import { ListingTeaser } from "../../02-molecules/teaser/teaser.stories.js";
import teaserListingTwig from "../../02-molecules/teaser/teaser--listing.twig";

export default {
  title: "Design System/Organisms/Teaser Collection",
};

const ListingTeaserTemplate1 = (args) => teaserListingTwig({
  ...ListingTeaser.args,
});
const ListingTeaserTemplate2 = (args) => teaserListingTwig({
  ...ListingTeaser.args,
});
const ListingTeaserTemplate3 = (args) => teaserListingTwig({
  ...ListingTeaser.args,
});

const teaserCollectionTemplate = ({ attributes, title, items }) =>
  teaserCollectionTwig({
    attributes,
    title,
    items
  });

export const teaserCollection = teaserCollectionTemplate.bind({});

teaserCollection.args = {
  attributes: new DrupalAttributes(),
  title: 'Teaser Collection',
  items: { ListingTeaserTemplate1, ListingTeaserTemplate2, ListingTeaserTemplate3 },
  link: '<a href="#">Read more content</a>'
}
