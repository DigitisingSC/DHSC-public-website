import React from 'react';
import DrupalAttribute from '../../../.storybook/drupalAttributes';
import bannerRegion from "./banner-region.twig";
import "./banner-region.scss";

import { Banner } from '../../02-molecules/banner/banner.stories';
import banner from '../../02-molecules/banner/banner.twig'

export default {
  title: "Design System/Organisms/Banner Region",
};

const Template = ({ content }) =>
  bannerRegion({
    content
  });

const BannerTemplate = (args) => Banner({
  ...Banner.args
});

export const BannerRegion = Template.bind({});
BannerRegion.args = {
  content: BannerTemplate
};
