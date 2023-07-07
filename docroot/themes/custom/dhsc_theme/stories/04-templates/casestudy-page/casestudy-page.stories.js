import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import caseStudyPage from "./casestudy-page.twig";

export default {
  title: "Design System/Templates/Case study page",
};

import image from '../../assets/images/featured-item.png';
const imgTag = `<div><img src=${image} alt='Digital Social Care'/></div>`

const Template = ({ title, image, lead, content }) =>
  caseStudyPage({
    title,
    image,
    lead,
    content
  });

export const CaseStudyPage= Template.bind({});
CaseStudyPage.args = {
  title: "Using technology for a seamless recruitment process",
  image: imgTag,
  lead: 'Blissfull Care Homes',
  content: "Case study content.."
};
