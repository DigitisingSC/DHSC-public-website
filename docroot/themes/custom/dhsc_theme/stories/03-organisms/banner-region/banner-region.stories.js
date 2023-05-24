import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import bannerRegion from "./banner-region.twig";

import { Banner } from '../../02-molecules/banner/banner.stories';
import banner from '../../02-molecules/banner/banner.twig'

export default {
  title: "Design System/Organisms/Banner Region",
};

const Template = ({ attributes, content }) =>
  bannerRegion({
    attributes,
    content
  });

const BannerTemplate = (args) => Banner({
  ...Banner.args
});

export const BannerRegion = Template.bind({});
BannerRegion.args = {
  attributes: new DrupalAttributes(),
  content: BannerTemplate
};
