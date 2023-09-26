import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import featuredItemsTwig from "./featured-items.twig";
import './featured-items.scss';

import { FeaturedItem } from '../../02-molecules/featured-item/featured-item.stories';
import featuredItemTwig from '../../02-molecules/featured-item/featured-item.twig';

export default {
  title: "Design System/Organisms/Featured items",
};

const Template = ({ attributes, layout, title, items, link_url, link_text }) =>
  featuredItemsTwig({
    attributes,
    layout,
    title,
    items,
    link_url,
    link_text
  });

const featuredItemTemplate1 = (args) => featuredItemTwig({
  ...FeaturedItem.args,
});
const featuredItemTemplate2 = (args) => featuredItemTwig({
  ...FeaturedItem.args,
  image: ''
});
const featuredItemTemplate3 = (args) => featuredItemTwig({
  ...FeaturedItem.args,
  image: ''
});
const featuredItemTemplate4 = (args) => featuredItemTwig({
  ...FeaturedItem.args,
  image: ''
});

export const FeaturedItems = Template.bind({});

FeaturedItems.args = {
  attributes: new DrupalAttributes(),
  layout: 4,
  title: 'Featured items',
  items: { featuredItemTemplate1, featuredItemTemplate2, featuredItemTemplate3, featuredItemTemplate4 },
  link_url: '#',
  link_text: 'See all content'
}
