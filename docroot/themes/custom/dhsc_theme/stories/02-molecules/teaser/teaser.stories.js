import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import './teaser.scss';
import teaserListingTwig from "./teaser--listing.twig";
import teaserSupplierTwig from "./teaser--supplier.twig";
import image from '../../assets/images/content-card.jpg';
const imgTag = `<img src=${image} alt='Digital Social Care'/>`

export default {
  title: "Design System/Molecules/Teaser",
};

const ListingTeaserTemplate = ({ attributes, variant, title, link, date }) =>
  teaserListingTwig({
    attributes,
    variant,
    title,
    link,
    date
  });

const SupplierTeaserTemplate = ({ attributes, variant, title, link, body, listPrice, image }) =>
  teaserSupplierTwig({
    attributes,
    variant,
    title,
    link,
    body,
    listPrice,
    image
  });

export const ListingTeaser = ListingTeaserTemplate.bind({});
ListingTeaser.args = {
  attributes: new DrupalAttributes(),
  variant: 'listing',
  title: "Get help using technology at your organisation",
  link: "https://www.digitalsocialcare.co.uk",
  date: "12 May 2023"
};


export const SupplierTeaser = SupplierTeaserTemplate.bind({});
SupplierTeaser.args = {
  attributes: new DrupalAttributes(),
  variant: 'supplier',
  title: "Get help using technology at your organisation",
  link: "https://www.digitalsocialcare.co.uk",
  body: "Body text",
  listPrice: "£150",
  image: imgTag
};
