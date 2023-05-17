import DrupalAttribute from '../../../.storybook/drupalAttributes';
import React from 'react';
import navigation from "./navigation.twig";
import { MainMenu } from "../../02-molecules/menus/menu-main.stories";
import MainMenuTwig from "../../02-molecules/menus/menu-main.twig";
export default {
  title: "Design System/Organisms/Navigation",
  content: MainMenu
};

const Template = ({ content, attributes }) =>
  navigation({
    content,
    attributes
  });

const MainMenuTemplate = (args) => MainMenuTwig({
  ...MainMenuTwig.args
});

export const Navigation = Template.bind({});
Navigation.args = {
  content: MainMenuTemplate,
  attributes: new DrupalAttribute(),
}
