import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import ctaBanner from "./cta-banner.twig";
import "./cta-banner.scss";
import image from '../../assets/images/content-card.jpg';
const imgTag = `<img src=${image} alt="Digital Social Care"/>`

export default {
  title: "Design System/Molecules/CTA Banner",
  component: ctaBanner,
  argTypes: {
    layout: {
      options: ['left', 'right'],
      control: { type: 'select' },
      defaultValue: 'left'
    },
  },

};

const Template = ({
  attributes,
  layout,
  header,
  title,
  link,
  description,
  secondary_title,
  media,
  secondary_link_url,
  secondary_link_text,
  }) =>
  ctaBanner({
    attributes,
    layout,
    header,
    title,
    link,
    description,
    secondary_title,
    media,
    secondary_link_url,
    secondary_link_text,
  });

export const CTABanner = Template.bind({});
CTABanner.args = {
  attributes: new DrupalAttributes(),
  layout: "left",
  header: "CTA Banner header",
  title: "CTA Banner",
  link: "#",
  description: "Nam pretium turpis et arcu. Aenean imperdiet. Maecenas malesuada. Etiam vitae tortor. Aenean imperdiet. Nam ipsum risus, rutrum vitae, vestibulum eu, molestie vel, lacus. Mauris sollicitudin fermentum libero. Vestibulum facilisis, purus nec pulvinar iaculis, ligula mi congue nunc, vitae euismod ligula urna in dolor. - Description",
  secondary_title: "Secondary title",
  media: imgTag,
  secondary_link_url:"#",
  secondary_link_text: "See all",
};
