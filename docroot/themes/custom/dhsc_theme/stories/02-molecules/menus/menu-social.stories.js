import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import menuSocialTwig from "./menu-social.twig";
import './menu-social.scss';
import { svgIcon } from '../../01-atoms/svg/svg.stories';
import svgIconTwig from '../../01-atoms/svg/svg.twig';

export default {
  title: "Design System/Molecules/Social Menu",
};

const svgIconTwitterTemplate = (args) => svgIconTwig({
  ...svgIcon.args,
  icon: 'twitter',
});

const svgIconFacebookTemplate = (args) => svgIconTwig({
  ...svgIcon.args,
  icon: 'facebook',
});

const svgIconLinkedinTemplate = (args) => svgIconTwig({
  ...svgIcon.args,
  icon: 'linkedin',
});

const svgIconYoutubeTemplate = (args) => svgIconTwig({
  ...svgIcon.args,
  icon: 'youtube',
});

const svgIconInstagramTemplate = (args) => svgIconTwig({
  ...svgIcon.args,
  icon: 'instagram',
});

const svgIconDefaultTemplate = (args) => svgIconTwig({
  ...svgIcon.args,
  icon: 'globe',
});


const Template = ({ items, attributes }) =>
  menuSocialTwig({
    items,
    attributes,
  });

export const MenuSocial = Template.bind({});
MenuSocial.args = {
  attributes: new DrupalAttributes(),
  items: [
    { title: 'Item 1', url: '#', 'icon': svgIconTwitterTemplate },
    { title: 'Item 2', url: '#', 'icon': svgIconFacebookTemplate },
    { title: 'Item 3', url: '#', 'icon': svgIconLinkedinTemplate },
    { title: 'Item 4', url: '#', 'icon': svgIconYoutubeTemplate },
    { title: 'Item 5', url: '#', 'icon': svgIconInstagramTemplate },
    { title: 'Item 6', url: '#', 'icon': svgIconDefaultTemplate },
  ],
};
