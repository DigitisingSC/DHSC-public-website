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

const SkillTeaserTemplate = ({ attributes, variant, title, subtitle, link, description, skillAttributes }) =>
  teaserListingTwig({
    attributes,
    variant,
    title,
    subtitle,
    link,
    description,
    skillAttributes,
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

export const SkillTeaser = SkillTeaserTemplate.bind({});
SkillTeaser.args = {
  attributes: new DrupalAttributes(),
  variant: 'listing',
  title: "Level 2 certificate in IT skills",
  subtitle: "A1 Social Care Training",
  link: "https://www.digitalsocialcare.co.uk",
  description: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Volutpat lobortis id viverra viverra ultrices. Dolor sit ultrices diam nunc, lorem morbi.",
  skillAttributes: {
    level: 'Beginner',
    length: '2 days',
    format: 'Online'
  }
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
