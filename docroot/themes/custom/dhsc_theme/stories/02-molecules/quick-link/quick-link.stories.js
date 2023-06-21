import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import './quick-link.scss';
import quickLinkTwig from "./quick-link.twig";

export default {
  title: "Design System/Molecules/Quick Link",
  argTypes: {
    quickLinkType: {
      control: { type: 'select'},
      options: ['green-20', 'green-60', 'burgundy', 'forest-100']
    },
  },
};

import image from '../../assets/images/content-card.jpg';
const imgTag = `<img src=${image} alt='Digital Social Care'/>`


const Template = ({ image, link, heading, description, attributes, quickLinkType }) =>
  quickLinkTwig({
    image,
    link,
    heading,
    description,
    attributes,
    quickLinkType
  });

export const quickLink = Template.bind({});
quickLink.args = {
  image: imgTag,
  link: "https://www.digitalsocialcare.co.uk/",
  heading: "Get help using technology at your organisation",
  description: "Learn how to get connected, use secure email, use mobile devices and get digital social care records",
  attributes: new DrupalAttributes()
};
