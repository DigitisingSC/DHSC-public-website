import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import bannerRegion from "./banner-region.twig";

import { Hero } from '../../02-molecules/hero/hero.stories';
import HeroTwig from '../../02-molecules/hero/hero.twig'

export default {
  title: "Design System/Organisms/Banner Region",
};

const Template = ({ attributes, content }) =>
  bannerRegion({
    attributes,
    content
  });

const HeroTemplate = (args) => Hero({
  ...Hero.args
});

export const BannerRegion = Template.bind({});
BannerRegion.args = {
  attributes: new DrupalAttributes(),
  content: HeroTemplate
};
