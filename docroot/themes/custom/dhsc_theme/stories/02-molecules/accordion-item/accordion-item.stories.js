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

const accordionSkillFrameworkTemplate = ({ title, items, icon_open, icon_close, variant }) =>
  accordionItemSkillsFrameworkTwig({
    title,
    items,
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
  title: "Digital skills go further",
  items: "<tr><td data-label='The criteria is met when...'><p>I can communicate how technologies such as digital social care records (DSCRs) can help people stay safe while maintaining their independence, and reduce time on admin tasks to allow more time for care and interaction</p></td><td data-label='I have the skills and knowledge to...'><p>6. Help my colleagues to learn about and understand the benefits of technology for person-centred care</p></td><td data-label='Learning resources'><a href='/government-drive-digital-transformation-adult-social-care-sector'>Useful link</a><a href='/government-drive-digital-transformation-adult-social-care-sector'>Useful link 2</a></td></tr>",
  variant: 'skills-framework'
};
