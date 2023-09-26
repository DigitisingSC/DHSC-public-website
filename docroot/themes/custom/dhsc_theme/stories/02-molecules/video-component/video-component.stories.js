import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import videoComponentTwig from "./video-component.twig";
import { video } from '../../01-atoms/video/video.stories';
import videoTwig from '../../01-atoms/video/video.twig';
import './video-component.scss';

export default {
  title: "Design System/Molecules/Video Component",
};

const Template = ({ attributes, header, description, video, caption }) =>
  videoComponentTwig({
    attributes,
    header,
    description,
    video,
    caption,
  });

const videoTemplate = (args) => videoTwig({
  ...video.args
});

export const videoComponent = Template.bind({});
videoComponent.args = {
  attributes: new DrupalAttributes(),
  header: "Video",
  description: "Video description, Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Phasellus ullamcorper ipsum rutrum nunc. Phasellus tempus. Suspendisse faucibus, nunc et pellentesque egestas, lacus ante convallis tellus, vitae iaculis lacus elit id tortor. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc, quis gravida magna mi a libero.",
  video: videoTemplate,
  caption: 'Netflix - Our planet - full episode',
};


const videoTemplate2 = (args) => videoTwig({
  ...video.args
});

export const videoComponent2 = Template.bind({});
videoComponent2.args = {
  attributes: new DrupalAttributes(),
  description: "Video description, Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Phasellus ullamcorper ipsum rutrum nunc. Phasellus tempus. Suspendisse faucibus, nunc et pellentesque egestas, lacus ante convallis tellus, vitae iaculis lacus elit id tortor. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc, quis gravida magna mi a libero.",
  video: videoTemplate2,
};
