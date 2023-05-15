import React from 'react';
import DrupalAttribute from '../../../.storybook/drupalAttributes';
import menuItem from "./menu-main.twig";

 export default {
   title: "Design System/Molecules/Menu Main",
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

 export const MenuMain = Template.bind({});
 MenuMain.args = {
   text: "Text settings",
 };
