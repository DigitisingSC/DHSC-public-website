import React from 'react';
import DrupalAttribute from '../../../.storybook/drupalAttributes';
import bannerRegion from "./banner-region.twig";
import { Banner } from '../../02-molecules/banner/banner.stories';
import banner from '../../02-molecules/banner/banner.twig'
import "./banner-region.scss";
import FooterSecondTwig from '../footer/footer-second.twig';
import { FooterSecond } from '../footer/footer.stories';
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
