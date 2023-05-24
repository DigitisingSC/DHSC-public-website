import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import articlePage from "./article-page.twig";

export default {
  title: "Design System/Templates/Article page",
};

const Template = ({ title, article_details, content }) =>
  articlePage({
    title,
    article_details,
    content
  });

export const ArticlePage = Template.bind({});
ArticlePage.args = {
  title: "Article page",
  article_details: "article details..",
  content: "Article page content.."
};
