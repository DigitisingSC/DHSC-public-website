import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import EventPageTwig from "./event-page.twig";
import './event-page.scss';

import {RelatedInformation} from "../../03-organisms/related-information/related-information.stories.js";
import relatedInformationTwig from "../../03-organisms/related-information/related-information.twig";

import {EventDetailsTime} from "../../02-molecules/event-details/event-details.stories.js";
import eventDetailsTimeTwig from "../../02-molecules/event-details/event-details--time.twig";

import image from '../../../assets/images/banner.jpg';
const imgTag = `<img src=${image} alt='Digital Social Care Banner'/>`;

const relatedInformationTemplate = (args) => relatedInformationTwig({
  ...RelatedInformation.args
});

const eventDetailsTemplate = (args) => eventDetailsTimeTwig({
  ...EventDetailsTime.args
});


export default {
  title: "Design System/Templates/Event Page",
};

const Template = ({ attributes, title, subtitle, event_details, featured_image, related, content }) =>
  EventPageTwig({
    attributes,
    title,
    subtitle,
    featured_image,
    event_details,
    related,
    content
  });

export const EventPage = Template.bind({});
EventPage.args = {
  attributes: new DrupalAttributes(),
  title: 'Get started with SharePoint Online',
  subtitle: 'By Digitising Social Care',
  featured_image: imgTag,
  event_details: eventDetailsTemplate,
  related: relatedInformationTemplate,
  content: `Proin magna. Duis arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis, ipsum. Praesent ac sem eget est egestas volutpat. Proin sapien ipsum, porta a, auctor quis, euismod ut, mi. In consectetuer turpis ut velit.

  Aenean vulputate eleifend tellus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Cras ultricies mi eu turpis hendrerit fringilla. Sed in libero ut nibh placerat accumsan. Aliquam lobortis.

  Curabitur blandit mollis lacus. Sed cursus turpis vitae tortor. Mauris turpis nunc, blandit et, volutpat molestie, porta ut, ligula. Donec interdum, metus et hendrerit aliquet, dolor diam sagittis ligula, eget egestas libero turpis vel mi. Proin faucibus arcu quis ante.

  Vivamus consectetuer hendrerit lacus. Phasellus accumsan cursus velit. Cras dapibus. Fusce a quam. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum.

  Quisque ut nisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Quisque rutrum. Suspendisse non nisl sit amet velit hendrerit rutrum. Aenean viverra rhoncus pede.`
};
