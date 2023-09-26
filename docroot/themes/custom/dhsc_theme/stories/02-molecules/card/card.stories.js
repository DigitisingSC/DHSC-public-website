import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import cardArticleTwig from "./card--article.twig";
import cardEventTwig from "./card--event.twig";
import cardCaseStudyTwig from "./card--casestudy.twig";
import './card.scss';

export default {
  title: "Design System/Molecules/Card",
};

import image from '../../assets/images/content-card.jpg';
const imgTag = `<img src=${image} alt='Digital Social Care'/>`

const cardArticleTemplate = ({ image, link, heading, description, date, attributes }) =>
  cardArticleTwig({
    image,
    link,
    heading,
    description,
    date,
    attributes
  });

const cardEventTemplate = ({ image, link, heading, description, date, type, attributes }) =>
  cardEventTwig({
    image,
    link,
    heading,
    description,
    date,
    type,
    attributes
  });

const cardCaseStudyTemplate = ({ image, link, heading, description, attributes }) =>
  cardCaseStudyTwig({
    image,
    link,
    heading,
    description,
    attributes
  });

export const cardArticle = cardArticleTemplate.bind({});
cardArticle.args = {
  image: imgTag,
  link: "https://www.digitalsocialcare.co.uk/",
  heading: "Get help using technology at your organisation",
  description: "Description goes here",
  date: "12 May 2023",
  attributes: new DrupalAttributes()
};

export const cardEvent = cardEventTemplate.bind({});
cardEvent.args = {
  image: imgTag,
  link: "https://www.digitalsocialcare.co.uk/",
  heading: "Get help using technology at your organisation",
  description: "Description goes here",
  date: "Thursday 12 May 2023",
  type: 'online',
  attributes: new DrupalAttributes()
};

export const cardCaseStudy = cardCaseStudyTemplate.bind({});
cardCaseStudy.args = {
  image: imgTag,
  link: "https://www.digitalsocialcare.co.uk/",
  heading: "Get help using technology at your organisation",
  description: "Description goes here",
  attributes: new DrupalAttributes()
};
