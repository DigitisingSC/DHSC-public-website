import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import accordionItem from "./accordion-item.twig";
import "./accordion-item.scss";

export default {
  title: "Design System/Molecules/Accordion Item",
};

const Template = ({ title, text }) =>
  accordionItem({
    title,
    text,
  });

export const AccordionItem = Template.bind({});
AccordionItem.args = {
  attributes: new DrupalAttributes(),
  title: "Digital skills for all",
  text: "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.",
};
