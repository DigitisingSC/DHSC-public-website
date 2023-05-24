import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import videoComponentTwig from "./video-component.twig";
import './video-component.scss';

export default {
  title: "Design System/Molecules/Video Component",
};

const Template = ({ header, description, media_thumbnail, media_thumbnail_legacy, iframe }) =>
  videoComponentTwig({
    header,
    description,
    media_thumbnail,
    media_thumbnail_legacy,
    iframe
  });

export const videoComponent = Template.bind({});
videoComponent.args = {
  header: "Video",
  description: "Video description",
  media_thumbnail: "//i.ytimg.com/vi_webp/JkaxUblCGz0/maxresdefault.webp",
  media_thumbnail_legacy: "//i.ytimg.com/vi/JkaxUblCGz0/maxresdefault.jpg",
  iframe: "<iframe class='' frameborder='0' allow='autoplay' data-src='https://www.youtube.com/embed/JkaxUblCGz0?autoplay=1'></iframe>"
};
