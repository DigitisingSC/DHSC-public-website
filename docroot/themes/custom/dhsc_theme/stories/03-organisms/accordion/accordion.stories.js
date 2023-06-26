import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import accordion from "./accordion.twig";
import { AccordionItem } from "../../02-molecules/accordion-item/accordion-item.stories.js";
import AccordionItemTwig from "../../02-molecules/accordion-item/accordion-item.twig";

import "./accordion.scss";

export default {
  title: "Design System/Organisms/Accordion",
};

const AccordionItemTemplate1 = (args) => AccordionItemTwig({
  ...AccordionItem.args,
});

const AccordionItemTemplate2 = (args) => AccordionItemTwig({
  ...AccordionItem.args,
});

const AccordionItemTemplate3 = (args) => AccordionItemTwig({
  ...AccordionItem.args,
});

const accordionTemplate = ({ header, items }) =>
  accordion({
    header,
    items,
  });

export const Accordion = accordionTemplate.bind({});

Accordion.args = {
  attributes: new DrupalAttributes(),
  header: "Digital skills accordion",
  items: { AccordionItemTemplate1, AccordionItemTemplate2, AccordionItemTemplate3 },
};
