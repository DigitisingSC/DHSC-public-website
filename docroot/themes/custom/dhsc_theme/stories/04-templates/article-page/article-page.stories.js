import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import articlePage from "./article-page.twig";

export default {
  title: "Design System/Templates/Article page",
};

const Template = ({ attributes, title, article_details, content }) =>
  articlePage({
    attributes,
    title,
    article_details,
    content
  });

export const ArticlePage = Template.bind({});
ArticlePage.args = {
  attributes: new DrupalAttributes(),
  title: "Article page",
  article_details: {
    author: "Jane Doe",
    date: '2014-07-02',
  },
  content: `Vestibulum turpis sem, aliquet eget, lobortis pellentesque, rutrum eu, nisl. Donec mollis hendrerit risus. Praesent blandit laoreet nibh. Suspendisse enim turpis, dictum sed, iaculis a, condimentum nec, nisi. Phasellus gravida semper nisi.

  Duis lobortis massa imperdiet quam. Curabitur vestibulum aliquam leo. Vestibulum ullamcorper mauris at ligula. Ut non enim eleifend felis pretium feugiat. Nullam tincidunt adipiscing enim.

  Vivamus consectetuer hendrerit lacus. Proin viverra, ligula sit amet ultrices semper, ligula arcu tristique sapien, a accumsan nisi mauris ac eros. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Aliquam eu nunc. Etiam rhoncus.

  Nunc nulla. Vivamus laoreet. Nullam quis ante. Pellentesque commodo eros a enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.

  In hac habitasse platea dictumst. Ut varius tincidunt libero. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc, quis gravida magna mi a libero. Integer ante arcu, accumsan a, consectetuer eget, posuere ut, mauris. Aenean ut eros et nisl sagittis vestibulum.`
};
