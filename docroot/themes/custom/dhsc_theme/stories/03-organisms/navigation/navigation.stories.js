import DrupalAttributes from '../../../.storybook/drupalAttributes';
import React from 'react';
import navigationPrimary from "./navigation--primary.twig";
import navigationSecondary from "./navigation--secondary.twig";
import PagerTwig from './pager.twig';
import './pager.scss';

import { svgIcon } from '../../01-atoms/svg/svg.stories';
import svgIconTwig from '../../01-atoms/svg/svg.twig';

import { MainMenu } from "../../02-molecules/menus/menu-main.stories";
import MainMenuTwig from "../../02-molecules/menus/menu-main.twig";
import { SecondaryMenu } from "../../02-molecules/menus/menu-secondary.stories";
import SecondaryMenuTwig from "../../02-molecules/menus/menu-secondary.twig";
export default {
  title: "Design System/Organisms/Navigation",
};

const svgIconLeftTemplate = (args) => svgIconTwig({
  ...svgIcon.args,
  icon: 'arrow-left',
});

const svgIconRightTemplate = (args) => svgIconTwig({
  ...svgIcon.args,
  icon: 'arrow-right',
});

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

const PagerTemplate = ({ attributes, iconArrowLeft, iconArrowRight, heading_id, items }) => PagerTwig({
  attributes,
  iconArrowLeft,
  iconArrowRight,
  heading_id,
  items,
});

const MainMenuTemplate = (args) => MainMenuTwig({
  ...MainMenu.args
});

const SecondaryMenuTemplate = (args) => SecondaryMenuTwig({
  ...SecondaryMenu.args
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
  content: SecondaryMenuTemplate,
}

export const Pager = PagerTemplate.bind({});
Pager.args = {
  attributes: new DrupalAttributes(),
  iconArrowLeft: svgIconLeftTemplate,
  iconArrowRight: svgIconRightTemplate,
  heading_id: 'Pager',
  items: {
    next: {
      attributes: new DrupalAttributes(),
      href: '#',
      text: 'Next'
    },
    previous: {
      attributes: new DrupalAttributes(),
      href: '#',
      text: 'Previous'
    },
    pages: [
      {
        attributes: new DrupalAttributes(),
        href: '#',
      },
      {
        attributes: new DrupalAttributes(),
        href: '#',
      },
      {
        attributes: new DrupalAttributes(),
        href: '#',
      },
    ],
  }
}
