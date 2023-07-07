import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import './quick-link.scss';
import quickLinkTwig from "./quick-link.twig";
import { svgIcon } from '../../01-atoms/svg/svg.stories';
import svgIconTwig from '../../01-atoms/svg/svg.twig';

export default {
  title: "Design System/Molecules/Quick Link",
  argTypes: {
    quickLinkType: {
      control: { type: 'select'},
      options: ['green-20', 'green-60', 'burgundy', 'forest-100']
    },
  },
};

const svgIconTemplate = (args) => svgIconTwig({
  ...svgIcon.args,
  icon: 'card-arrow',
});

import image from '../../assets/images/content-card.jpg';
const imgTag = `<div><img src=${image} alt='Digital Social Care'/></div>`


const Template = ({ image, link, heading, description, attributes, quickLinkType, icon }) =>
  quickLinkTwig({
    image,
    link,
    heading,
    description,
    attributes,
    quickLinkType,
    icon
  });

export const quickLink = Template.bind({});
quickLink.args = {
  image: imgTag,
  link: "https://www.digitalsocialcare.co.uk/",
  heading: "Get help using technology at your organisation",
  description: "Learn how to get connected, use secure email, use mobile devices and get digital social care records",
  attributes: new DrupalAttributes(),
  icon: svgIconTemplate,
};
