import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import quickLinksTwig from "./quick-links.twig";
import './quick-links.scss';

import { quickLink } from "../../02-molecules/quick-link/quick-link.stories.js";
import quickLinkTwig from "../../02-molecules/quick-link/quick-link.twig";
import { svgIcon } from '../../01-atoms/svg/svg.stories';

export default {
  title: "Design System/Organisms/Quick Links",
};

import image from '../../assets/images/content-card.jpg';
const imgTag = `<div><img src=${image} alt='Digital Social Care'/></div>`

const svgIconTemplate = (args) => svgIcon({
  ...svgIcon.args,
  icon: 'card-arrow',
});

const quickLink1 = (args) => quickLinkTwig({
  ...quickLink1.args = {
    link: "https://www.digitalsocialcare.co.uk/",
    heading: "Get help using technology",
    description: "Learn how to get connected, use secure email, use mobile devices and get digital social care records",
    attributes: new DrupalAttributes(),
    icon: svgIconTemplate,
  },
});

const quickLink2 = (args) => quickLinkTwig({
  ...quickLink2.args = {
    link: "https://www.digitalsocialcare.co.uk/",
    heading: "Get help using technology",
    description: "Learn how to get connected, use secure email, use mobile devices and get digital social care records. Learn how to get connected, use secure email, use mobile devices and get digital social care records",
    quickLinkType: "burgundy",
    attributes: new DrupalAttributes(),
    icon: svgIconTemplate,
  },
});

const quickLink3 = (args) => quickLinkTwig({
  ...quickLink.args,
});

const quickLink4 = (args) => quickLinkTwig({
  ...quickLink4.args = {
    image: imgTag,
    link: "https://www.digitalsocialcare.co.uk/",
    heading: "Get help using technology at your organisation",
    description: "Fusce vel dui. Etiam ultricies nisi vel augue. Aliquam lobortis. Sed magna purus, fermentum eu, tincidunt eu, varius ut, felis. Aliquam eu nunc. Pellentesque egestas, neque sit amet convallis pulvinar, justo nulla eleifend augue, ac auctor orci leo non est. Duis arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis, ipsum.",
    attributes: new DrupalAttributes(),
    icon: svgIconTemplate,
  }
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

export const quickLinks2 = quickLinksTemplate.bind({});

quickLinks2.args = {
  attributes: new DrupalAttributes(),
  title: 'Quick Links',
  items_left: { quickLink3 },
  items_right: { quickLink4 },
}

export const quickLinks3 = quickLinksTemplate.bind({});

quickLinks3.args = {
  attributes: new DrupalAttributes(),
  title: 'Quick Links',
  items_left: { quickLink4, quickLink2 },
  items_right: { quickLink3, quickLink1 },
}

export const quickLinks4 = quickLinksTemplate.bind({});

quickLinks4.args = {
  attributes: new DrupalAttributes(),
  title: 'Quick Links',
  items_left: { quickLink1, quickLink2 },
  items_right: { quickLink3, quickLink1 },
}
