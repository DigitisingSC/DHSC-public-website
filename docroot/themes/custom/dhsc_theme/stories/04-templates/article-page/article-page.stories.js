import React from 'react';
import DrupalAttribute from '../../../.storybook/drupalAttributes';
import articlePage from "./article-page.twig";

export default {
  title: "Design System/Templates/Article page",
};

const Template = ({ article_details, content }) =>
  articlePage({
    article_details,
    content
  });

export const ArticlePage = Template.bind({});
ArticlePage.args = {
  article_details: "article details..",
  content: "article page.."
};
