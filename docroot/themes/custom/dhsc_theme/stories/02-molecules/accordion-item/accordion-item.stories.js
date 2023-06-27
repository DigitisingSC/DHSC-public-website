import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import accordionItemDefaultTwig from "./accordion-item--default.twig";
import accordionItemSkillsFrameworkTwig from "./accordion-item--skills-framework.twig";
import { svgIcon } from '../../01-atoms/svg/svg.stories';
import svgIconTwig from '../../01-atoms/svg/svg.twig';
import "./accordion-item.scss";

export default {
  title: "Design System/Molecules/Accordion Item",
};

const svgIconOpenTemplate = (args) => svgIconTwig({
  ...svgIcon.args,
  icon: 'accordion-open',
});

const svgIconCloseTemplate = (args) => svgIconTwig({
  ...svgIcon.args,
  icon: 'accordion-close',
});

const accordionDefaultTemplate = ({ title, text, icon_open, icon_close, variant }) =>
  accordionItemDefaultTwig({
    title,
    text,
    icon_open,
    icon_close,
    variant
  });

const accordionSkillFrameworkTemplate = ({ title, text, icon_open, icon_close, variant }) =>
  accordionItemSkillsFrameworkTwig({
    title,
    text,
    icon_open,
    icon_close,
    variant
  });

export const AccordionItemDefault = accordionDefaultTemplate.bind({});
AccordionItemDefault.args = {
  attributes: new DrupalAttributes(),
  icon_open: svgIconOpenTemplate,
  icon_close: svgIconCloseTemplate,
  title: "Digital skills for all",
  text: "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.",
  variant: 'default'
};


export const AccordionItemSkillsFramework = accordionSkillFrameworkTemplate.bind({});
AccordionItemSkillsFramework.args = {
  attributes: new DrupalAttributes(),
  icon_open: svgIconOpenTemplate,
  icon_close: svgIconCloseTemplate,
  title: "Digital skills for all",
  text: "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.",
  variant: 'skills-framework'
};
