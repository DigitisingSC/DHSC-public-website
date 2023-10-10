import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import gridTwig from "./content-grid.twig";
import './content-grid.scss';
import { cardEvent } from "../../02-molecules/card/card.stories.js";
import cardEventTwig from "../../02-molecules/card/card--event.twig";

export default {
  title: "Design System/Organisms/Content grid",
  argTypes: {
    columns: {
      control: { type: 'select'},
      options: [
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
        '10',
        '11',
        '12',
      ]
    },
  },
};

const gridTemplate = ({ attributes, columns, title, items }) =>
  gridTwig({
    attributes,
    columns,
    title,
    items,
  });

const cardEventTemplate = (args) => cardEventTwig({
  ...cardEvent.args
});

const cardEvents = [
  cardEventTemplate,
  cardEventTemplate,
  cardEventTemplate,
  cardEventTemplate,
  cardEventTemplate,
  cardEventTemplate,
  cardEventTemplate,
  cardEventTemplate,
  cardEventTemplate,
];

export const ContentGrid = gridTemplate.bind({});
ContentGrid.args = {
  attributes: new DrupalAttributes(),
  title: '',
  columns: 3,
  items: cardEvents,
};
