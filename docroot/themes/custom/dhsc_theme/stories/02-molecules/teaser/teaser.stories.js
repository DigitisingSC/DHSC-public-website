import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import './teaser.scss';
import teaserTwig from "./teaser.twig";

export default {
  title: "Design System/Molecules/Teaser",
};

const Template = ({ attributes, title, link, date }) =>
  teaserTwig({
    attributes,
    title,
    link,
    date
  });

export const Teaser = Template.bind({});
Teaser.args = {
  attributes: new DrupalAttributes(),
  title: "Get help using technology at your organisation",
  link: "https://www.digitalsocialcare.co.uk",
  date: "12 May 2023"
};
