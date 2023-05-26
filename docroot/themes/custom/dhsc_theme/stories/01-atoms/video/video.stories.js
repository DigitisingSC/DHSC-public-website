import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import videoTwig from "./video.twig";
import videoBehaviour from "./video.js";
import { svgIcon } from '../svg/svg.stories';
import './video.scss';

export default {
  title: "Design System/Atoms/Video",
};

const Template = ({attributes, iframe, media_thumbnail, media_thumbnail_legacy, icon }) =>
  videoTwig({
    attributes,
    iframe,
    media_thumbnail,
    media_thumbnail_legacy,
    icon
  });

const svgIconTemplate = (args) => svgIcon({
  ...svgIcon.args
});

export const video = Template.bind({});
video.args = {
  attributes: new DrupalAttributes(),
  iframe: "<iframe class='' frameborder='0' allow='autoplay' data-src='https://www.youtube.com/embed/JkaxUblCGz0?autoplay=1'></iframe>",
  media_thumbnail: "//i.ytimg.com/vi_webp/JkaxUblCGz0/maxresdefault.webp",
  media_thumbnail_legacy: "//i.ytimg.com/vi/JkaxUblCGz0/maxresdefault.jpg",
  icon: svgIconTemplate,
};
