import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import menuTwig from "./menu-main.twig";
import './menu-main.scss';
import { svgIcon } from '../../01-atoms/svg/svg.stories';
import svgIconTwig from '../../01-atoms/svg/svg.twig';

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

const Template = ({ items, attributes, svgIconUp, svgIconDown }) =>
  menuTwig({
    items,
    attributes,
    svgIconUp,
    svgIconDown
   });

 export const MainMenu = Template.bind({});
 MainMenu.args = {
   attributes: new DrupalAttributes(),
   items: [
     { title: 'Item 1', url: '#' },
     { title: 'Item 2', url: '#', below: [{ title: 'subitem 1', url: '#'}, { title: 'subitem 2', url: '#'}] },
     { title: 'Item 3', url: '#', below: [{ title: 'subitem A', url: '#'}, { title: 'subitem B', url: '#'}] },
   ],
   svgIconUp: svgIconUpTemplate,
   svgIconDown: svgIconDownTemplate
 };
