import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import videoComponentTwig from "./video-component.twig";
import { video } from '../../01-atoms/video/video.stories';
import videoTwig from '../../01-atoms/video/video.twig';
import './video-component.scss';

export default {
  title: "Design System/Molecules/Video Component",
};

const Template = ({ attributes, header, description, video }) =>
  videoComponentTwig({
    attributes,
    header,
    description,
    video
  });

const videoTemplate = (args) => videoTwig({
  ...video.args
});

export const videoComponent = Template.bind({});
videoComponent.args = {
  attributes: new DrupalAttributes(),
  header: "Video",
  description: "Video description",
  video: videoTemplate,
};
