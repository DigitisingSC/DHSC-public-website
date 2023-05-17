import cardArticleTwig from "./card--article.twig";
import cardEventTwig from "./card--event.twig";
import cardCaseStudyTwig from "./card--casestudy.twig";

export default {
  title: "Design System/Molecules/Card",
};

import image from '../../assets/images/content-card.jpg';
const imgTag = `<img src=${image} alt='Digital Social Care'/>`

const cardArticleTemplate = ({ image, link, heading, description, date }) =>
  cardArticleTwig({
    image,
    link,
    heading,
    description,
    date
  });

const cardEventTemplate = ({ image, link, heading, description, date }) =>
  cardEventTwig({
    image,
    link,
    heading,
    description,
    date
  });

const cardCaseStudyTemplate = ({ image, link, heading, description }) =>
  cardCaseStudyTwig({
    image,
    link,
    heading,
    description,
  });

export const cardArticle = cardArticleTemplate.bind({});
cardArticle.args = {
  image: imgTag,
  link: "https://www.digitalsocialcare.co.uk/",
  heading: "Get help using technology at your organisation",
  description: "Description goes here",
  date: "12 May 2023"
};

export const cardEvent = cardEventTemplate.bind({});
cardEvent.args = {
  image: imgTag,
  link: "https://www.digitalsocialcare.co.uk/",
  heading: "Get help using technology at your organisation",
  description: "Description goes here",
  date: "Thursday 12 May 2023"
};

export const cardCaseStudy = cardCaseStudyTemplate.bind({});
cardCaseStudy.args = {
  image: imgTag,
  link: "https://www.digitalsocialcare.co.uk/",
  heading: "Get help using technology at your organisation",
  description: "Description goes here",
};
