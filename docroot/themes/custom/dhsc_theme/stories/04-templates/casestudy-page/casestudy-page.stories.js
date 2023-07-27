import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import caseStudyPage from "./casestudy-page.twig";

import {FurtherInformation} from "../../03-organisms/further-information/further-information.stories.js";
import furtherInformationTwig from "../../03-organisms/further-information/further-information.twig";

import './casestudy-page.scss';

export default {
  title: "Design System/Templates/Case study page",
};

import image from '../../assets/images/featured-item.png';
const imgTag = `<div><img src=${image} alt='Digital Social Care'/></div>`

const furtherInformationTemplate = (args) => furtherInformationTwig({
  ...FurtherInformation.args
});

const Template = ({ title, image, lead, content, further_information }) =>
  caseStudyPage({
    title,
    image,
    lead,
    content,
    further_information
  });

export const CaseStudyPage= Template.bind({});
CaseStudyPage.args = {
  title: "Using technology for a seamless recruitment process",
  image: imgTag,
  lead: 'Blissfull Care Homes',
  content: `<h2>Nulla neque dolor sagittis eget</h2>
  <p>Suspendisse eu ligula. Ut tincidunt tincidunt erat. Aenean commodo ligula eget dolor. Aliquam erat volutpat. Nunc sed turpis. Phasellus tempus. Aenean vulputate eleifend tellus. In ut quam vitae odio lacinia tincidunt. Etiam ut purus mattis mauris sodales aliquam. Praesent ut ligula non mi varius sagittis. Phasellus volutpat, metus eget egestas mollis, lacus lacus blandit dui, id egestas quam mauris ut lacus. Aenean vulputate eleifend tellus. Curabitur blandit mollis lacus. Mauris sollicitudin fermentum libero. Maecenas nec odio et ante tincidunt tempus.</p>
  <h3>Suspendisse nisl elit rhoncus eget</h3>
  <p>Praesent ac massa at ligula laoreet iaculis. Praesent adipiscing. Fusce neque. Sed cursus turpis vitae tortor. Etiam iaculis nunc ac metus. Phasellus consectetuer vestibulum elit. Ut tincidunt tincidunt erat. Vivamus in erat ut urna cursus vestibulum. Praesent congue erat at massa. Sed fringilla mauris sit amet nibh.</p>`,
  further_information: furtherInformationTemplate,
};
