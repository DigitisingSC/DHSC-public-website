import DrupalAttributes from '../../../.storybook/drupalAttributes';
import React from 'react';
import teaserCollectionTwig from "./teaser-collection.twig";
import './teaser-collection.scss';

import { Teaser } from "../../02-molecules/teaser/teaser.stories.js";
import teaserTwig from "../../02-molecules/teaser/teaser.twig";

export default {
  title: "Design System/Organisms/Teaser Collection",
};

const teaserTemplate1 = (args) => teaserTwig({
  ...Teaser.args,
});
const teaserTemplate2 = (args) => teaserTwig({
  ...Teaser.args,
});
const teaserTemplate3 = (args) => teaserTwig({
  ...Teaser.args,
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
  items: { teaserTemplate1, teaserTemplate2, teaserTemplate3 },
  link: '<a href="#">Read more content</a>'
}
