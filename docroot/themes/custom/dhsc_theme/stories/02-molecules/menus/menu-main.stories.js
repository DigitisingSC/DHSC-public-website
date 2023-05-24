import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import menuItem from "./menu-main.twig";

 export default {
   title: "Design System/Molecules/Main Menu",
   argTypes: {
     type: {
       control: {
         type: "inline-radio",
         options: ["Menu version 1", "Menu version", "Menu version 2"],
       },
     },
   },
 };

 const Template = ({ text, type }) =>
  menuItem({
     text,
     type,
   });

 export const MainMenu = Template.bind({});
 MainMenu.args = {
   text: "Text settings",
 };
