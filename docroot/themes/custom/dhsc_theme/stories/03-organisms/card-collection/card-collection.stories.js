import cardCollectionTwig from "./card-collection.twig";

import { cardArticle } from "../../02-molecules/card/card.stories.js";
import { cardEvent } from "../../02-molecules/card/card.stories.js";
import { cardCaseStudy } from "../../02-molecules/card/card.stories.js";

import cardArticeTwig from "../../02-molecules/card/card--article.twig";
import cardEventTwig from "../../02-molecules/card/card--event.twig";
import cardCaseStudyTwig from "../../02-molecules/card/card--casestudy.twig";

export default {
  title: "Design System/Organisms/Card Collection",
};

const cardArticleTemplate = (args) => cardArticeTwig({
  ...cardArticle.args
});

const cardEventTemplate = (args) => cardEventTwig({
  ...cardEvent.args
});

const cardCasestudyTemplate = (args) => cardCaseStudyTwig({
  ...cardCaseStudy.args
});

const cardCollectionTemplate = ({ title, items }) =>
  cardCollectionTwig({
    title,
    items
  });

export const cardCollection = cardCollectionTemplate.bind({});
cardCollection.args = {
  title: "Card collection title",
  items: cardArticleTemplate
};
