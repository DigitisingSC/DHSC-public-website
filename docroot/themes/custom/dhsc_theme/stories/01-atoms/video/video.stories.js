import React from 'react';
import DrupalAttribute from '../../../.storybook/drupalAttributes';
import videoTwig from "./video.twig";
import videoBehaviour from "./video.js";
import './video.scss';

export default {
  title: "Design System/Atoms/Video",
};

const Template = ({attributes, iframe, media_thumbnail, media_thumbnail_legacy}) =>
  videoTwig({
    attributes,
    iframe,
    media_thumbnail,
    media_thumbnail_legacy,
  });

export const video = Template.bind({});
video.args = {
  attributes: new DrupalAttribute(),
  iframe: "<iframe class='' frameborder='0' allow='autoplay' data-src='https://www.youtube.com/embed/JkaxUblCGz0?autoplay=1'></iframe>",
  media_thumbnail: "//i.ytimg.com/vi_webp/JkaxUblCGz0/maxresdefault.webp",
  media_thumbnail_legacy: "//i.ytimg.com/vi/JkaxUblCGz0/maxresdefault.jpg",
};
