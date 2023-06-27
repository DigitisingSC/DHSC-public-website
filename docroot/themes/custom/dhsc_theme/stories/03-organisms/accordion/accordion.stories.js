import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import accordionDefault from "./accordion--default.twig";
import { AccordionItemDefault } from "../../02-molecules/accordion-item/accordion-item.stories.js";
import AccordionItemDefaultTwig from "../../02-molecules/accordion-item/accordion-item--default.twig";
import accordionSkillsFramework from "./accordion--skills-framework.twig";
import { AccordionItemSkillsFramework } from "../../02-molecules/accordion-item/accordion-item.stories.js";
import AccordionItemSkillsFrameworkTwig from "../../02-molecules/accordion-item/accordion-item--skills-framework.twig";

import "./accordion.scss";

export default {
  title: "Design System/Organisms/Accordion",
};

const AccordionItemDefaultTemplate1 = (args) => AccordionItemDefaultTwig({
  ...AccordionItemDefault.args,
});

const AccordionItemDefaultTemplate2 = (args) => AccordionItemDefaultTwig({
  ...AccordionItemDefault.args,
});

const AccordionItemDefaultTemplate3 = (args) => AccordionItemDefaultTwig({
  ...AccordionItemDefault.args,
});

const AccordionItemSkillsFrameworkTemplate1 = (args) => AccordionItemSkillsFrameworkTwig({
  ...AccordionItemSkillsFramework.args,
});

const AccordionItemSkillsFrameworkTemplate2 = (args) => AccordionItemSkillsFrameworkTwig({
  ...AccordionItemSkillsFramework.args,
});

const AccordionItemSkillsFrameworkTemplate3 = (args) => AccordionItemSkillsFrameworkTwig({
  ...AccordionItemSkillsFramework.args,
});

const accordionDefaultTemplate = ({ header, items }) =>
  accordionDefault({
    header,
    items,
  });

const accordionSkillsFrameworkTemplate = ({ header, items }) =>
  accordionSkillsFramework({
    header,
    items,
  });

export const AccordionDefault = accordionDefaultTemplate.bind({});
AccordionDefault.args = {
  attributes: new DrupalAttributes(),
  header: "Digital skills accordion",
  items: { AccordionItemDefaultTemplate1, AccordionItemDefaultTemplate2, AccordionItemDefaultTemplate3 },
};

export const AccordionSkillsFramework = accordionSkillsFrameworkTemplate.bind({});
AccordionSkillsFramework.args = {
  attributes: new DrupalAttributes(),
  header: "Digital skills accordion",
  items: { AccordionItemSkillsFrameworkTemplate1, AccordionItemSkillsFrameworkTemplate2, AccordionItemSkillsFrameworkTemplate3 },
};
