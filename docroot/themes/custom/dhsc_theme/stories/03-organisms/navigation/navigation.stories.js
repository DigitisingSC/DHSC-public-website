import DrupalAttributes from '../../../.storybook/drupalAttributes';
import React from 'react';
import navigationPrimary from "./navigation--primary.twig";
import navigationSecondary from "./navigation--secondary.twig";
import { MainMenu } from "../../02-molecules/menus/menu-main.stories";
import MainMenuTwig from "../../02-molecules/menus/menu-main.twig";
export default {
  title: "Design System/Organisms/Navigation",
  variant: 'primary',
  content: MainMenu
};

const NavigationPrimaryTemplate = ({ attributes, variant, content }) =>
  navigationPrimary({
    attributes,
    variant,
    content,
  });

const NavigationSecondaryTemplate = ({ attributes, variant, content }) =>
  navigationSecondary({
    attributes,
    variant,
    content,
  });

const MainMenuTemplate = (args) => MainMenuTwig({
  ...MainMenuTwig.args
});

export const NavigationPrimary = NavigationPrimaryTemplate.bind({});
NavigationPrimary.args = {
  attributes: new DrupalAttributes(),
  variant: 'primary',
  content: MainMenuTemplate,
}

export const NavigationSecondary = NavigationSecondaryTemplate.bind({});
NavigationSecondary.args = {
  attributes: new DrupalAttributes(),
  variant: 'secondary',
  content: MainMenuTemplate,
}
