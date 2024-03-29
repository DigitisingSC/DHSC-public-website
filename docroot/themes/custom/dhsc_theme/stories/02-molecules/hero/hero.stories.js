import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import hero from "./hero.twig";
import "./hero.scss";

export default {
  title: "Design System/Molecules/Hero",
};

import image from '../../../assets/images/hero.png';
const imgTag = `<img src=${image} alt='Digital Social Care Banner'/>`;

const Template = ({ image, url, heading, text }) =>
  hero({
    image,
    url,
    heading,
    text,
  });

export const Hero = Template.bind({});
Hero.args = {
  image: imgTag,
  heading: "Digitising social care",
  text: "<p>Putting people at the heart of digital care</p><p>Watch our <a href='#'>video</a> to find out more</p>",
};
