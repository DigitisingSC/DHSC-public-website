import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import ctaBanner from "./cta-banner.twig";
import "./cta-banner.scss";
import image from '../../assets/images/content-card.jpg';
const imgTag = `<img src=${image} alt='Digital Social Care'/>`

export default {
  title: "Design System/Molecules/CTA Banner",
  component: ctaBanner,
  argTypes: {
    layout: {
      options: ['left', 'right'],
      control: { type: 'radio' },
      defaultValue: 'left'
    },
  },

};

const Template = ({
  attributes,
  layout,
  title,
  description,
  link,
  media,
  }) =>
  ctaBanner({
    attributes,
    layout,
    title,
    description,
    link,
    media,
  });

export const CTABanner = Template.bind({});
CTABanner.args = {
  attributes: new DrupalAttributes(),
  layout: "left",
  title: "CTA Banner",
  description: "This is a CTA Banner",
  link: "<a href='#'>This is a link</a>",
  media: imgTag,
};
