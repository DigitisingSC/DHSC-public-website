import React from 'react';
import DrupalAttribute from '../../../.storybook/drupalAttributes';
import articleDetails from "./article-details.twig";

export default {
  title: "Design System/Molecules/Article Details",
};

const Template = ({ date, author }) =>
  articleDetails({
    date,
    author,
  });

export const articleDetailsHTML = Template.bind({});
articleDetailsHTML.args = {
  date: "6 April 2023",
  author: "Matt Lambert",
};
