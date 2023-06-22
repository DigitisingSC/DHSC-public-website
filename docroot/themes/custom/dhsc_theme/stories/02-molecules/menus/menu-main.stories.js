import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import menuTwig from "./menu-main.twig";
import './menu-main.scss';
import { svgIcon } from '../../01-atoms/svg/svg.stories';
import svgIconTwig from '../../01-atoms/svg/svg.twig';
import FeaturedLinks from '../featured-links/featured-links.stories';
import featuredLinksTwig from '../featured-links/featured-links.twig';

 export default {
   title: "Design System/Molecules/Main Menu",
 };

const svgIconUpTemplate = (args) => svgIconTwig({
  ...svgIcon.args,
  icon: 'chevron-up',
});

const svgIconDownTemplate = (args) => svgIconTwig({
  ...svgIcon.args,
  icon: 'chevron-down',
});

const featuredLinksTemplate = (args) => featuredLinksTwig({
  ...FeaturedLinks.args,
  links: [
    {
      'url': '#',
      'title': 'Link 1',
      'description': 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s.'
    },
    {
      'url': '#',
      'title': 'Link 2',
      'description': 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s.'
    },
    {
      'url': '#',
      'title': 'Link 3',
      'description': 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s.'
    },
  ]

});

const Template = ({ items, attributes, svgIconUp, svgIconDown, featuredLinks }) =>
  menuTwig({
    items,
    attributes,
    svgIconUp,
    svgIconDown,
    featuredLinks
   });

 export const MainMenu = Template.bind({});
 MainMenu.args = {
   attributes: new DrupalAttributes(),
   items: [
     { title: 'Item 1', url: '#', below: [{ title: 'subitem 1', url: '#'}, { title: 'subitem 2', url: '#'}] },
     { title: 'Item 2', url: '#' },
     { title: 'Item 3', url: '#' },
   ],
   svgIconUp: svgIconUpTemplate,
   svgIconDown: svgIconDownTemplate,
   featuredLinks: featuredLinksTemplate
 };
