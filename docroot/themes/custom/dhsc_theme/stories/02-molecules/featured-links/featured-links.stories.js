import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import featuredLinksTwig from "./featured-links.twig";

export default {
  title: "Design System/Molecules/Featured links",
};

const Template = ({ links }) =>
  featuredLinksTwig({
    links
  });

export const FeaturedLinks = Template.bind({});
FeaturedLinks.args = {
  links: [
    {
      'url': '#',
      'title': 'Link 1',
      'description': 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s.'
    },
    {
      'url': '#',
      'title': 'Link 2',
      'description': 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s.'
    },
    {
      'url': '#',
      'title': 'Link 3',
      'description': 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s.'
    },
  ]
};
