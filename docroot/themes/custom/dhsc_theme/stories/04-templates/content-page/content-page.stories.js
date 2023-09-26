import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import contentPageDefault from "./content-page--default.twig";
import contentPageVariant from "./content-page--intro-above.twig";

export default {
  title: "Design System/Templates/Content page",
};

const contentPageDefaultTemplate = ({ attributes, title, intro, content }) =>
  contentPageDefault({
    attributes,
    title,
    intro,
    content
  });

const contentPageDefaultVariantTemplate = ({ attributes, title, intro, content }) =>
  contentPageVariant({
    attributes,
    title,
    intro,
    content
  });

export const ContentPageDefault = contentPageDefaultTemplate.bind({});
ContentPageDefault.args = {
  attributes: new DrupalAttributes(),
  title: "Content page",
  intro: "Intro..",
  content: "Content.."
};

export const ContentPageIntroAbove = contentPageDefaultVariantTemplate.bind({});
ContentPageIntroAbove.args = {
  attributes: new DrupalAttributes(),
  title: "Content page",
  intro: "Intro..",
  content: "Content.."
};
