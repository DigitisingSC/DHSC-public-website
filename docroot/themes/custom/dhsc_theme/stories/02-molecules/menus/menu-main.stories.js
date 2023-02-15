import menuItem from "./menu-main.twig";

import './../../../../../contrib/localgov_base/css/components/menu-main.ie11.css';
import './../../../../../contrib/localgov_base/css/components/menu-main.css';

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