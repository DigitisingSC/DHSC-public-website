import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import cardCollectionTwig from "./card-collection.twig";

import { cardArticle } from "../../02-molecules/card/card.stories.js";
import cardArticleTwig from "../../02-molecules/card/card--article.twig";
import { cardEvent } from "../../02-molecules/card/card.stories.js";
import cardEventTwig from "../../02-molecules/card/card--event.twig";
import { cardCaseStudy } from "../../02-molecules/card/card.stories.js";
import cardCaseStudyTwig from "../../02-molecules/card/card--casestudy.twig";

export default {
  title: "Design System/Organisms/Card Collection",
};

const cardArticleTemplate = (args) => cardArticleTwig({
  ...cardArticle.args,
});

const cardEventTemplate = (args) => cardEventTwig({
  ...cardEvent.args
});

const cardCaseStudyTemplate = (args) => cardCaseStudyTwig({
  ...cardCaseStudy.args
});

const cardCollectionTemplate = ({ attributes, title, items }) =>
  cardCollectionTwig({
    attributes,
    title,
    items
  });

export const cardCollection = cardCollectionTemplate.bind({});

cardCollection.args = {
  attributes: new DrupalAttributes(),
  title: 'Card Collection',
  items: { cardArticleTemplate, cardEventTemplate, cardCaseStudyTemplate },
}
