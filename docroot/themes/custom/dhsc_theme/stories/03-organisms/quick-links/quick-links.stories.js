import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import quickLinksTwig from "./quick-links.twig";
import './quick-links.scss';

import { cardArticle } from "../../02-molecules/card/card.stories.js";
import cardArticleTwig from "../../02-molecules/card/card--article.twig";
import { cardEvent } from "../../02-molecules/card/card.stories.js";
import cardEventTwig from "../../02-molecules/card/card--event.twig";
import { cardCaseStudy } from "../../02-molecules/card/card.stories.js";
import cardCaseStudyTwig from "../../02-molecules/card/card--casestudy.twig";

import { quickLink } from "../../02-molecules/quick-link/quick-link.stories.js";
import quickLinkTwig from "../../02-molecules/quick-link/quick-link.twig";


export default {
  title: "Design System/Organisms/Quick Links",
};

const quickLink1 = (args) => quickLinkTwig({
  ...quickLink1.args = {
    link: "https://www.digitalsocialcare.co.uk/",
    heading: "Get help using technology",
    description: "Learn how to get connected, use secure email, use mobile devices and get digital social care records",
    attributes: new DrupalAttributes()
  },
});

const quickLink2 = (args) => quickLinkTwig({
  ...quickLink2.args = {
    link: "https://www.digitalsocialcare.co.uk/",
    heading: "Get help using technology",
    description: "Learn how to get connected, use secure email, use mobile devices and get digital social care records. Learn how to get connected, use secure email, use mobile devices and get digital social care records",
    quickLinkType: "burgundy",
    attributes: new DrupalAttributes(),
  },
});

const quickLink3 = (args) => quickLinkTwig({
  ...quickLink.args,
});

const quickLinksTemplate = ({ attributes, title, items_left, items_right }) =>
  quickLinksTwig({
    attributes,
    title,
    items_left,
    items_right
  });

export const quickLinks = quickLinksTemplate.bind({});

quickLinks.args = {
  attributes: new DrupalAttributes(),
  title: 'Quick Links',
  items_left: { quickLink1, quickLink2 },
  items_right: { quickLink3 },
}
