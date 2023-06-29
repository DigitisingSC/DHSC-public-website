import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import featuredItemTwig from './featured-item.twig';
import './featured-item.scss';
import image from '../../assets/images/featured-item.png';
const imgTag = `<img src=${image} alt='Digital Social Care'/>`

export default {
  title: "Design System/Molecules/Featured item",
};

const Template = ({
  attributes,
  image,
  title,
  link,
  description,
  date
}) => featuredItemTwig({
  attributes,
  image,
  title,
  link,
  description,
  date,
});

export const FeaturedItem = Template.bind({});
FeaturedItem.args = {
  attributes: new DrupalAttributes(),
  image: imgTag,
  title: "Featured item",
  link: '#',
  description: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Arcu, nisi consectetur risus sit sit sit pretium.",
  date: "12 Jun 2023",
};
