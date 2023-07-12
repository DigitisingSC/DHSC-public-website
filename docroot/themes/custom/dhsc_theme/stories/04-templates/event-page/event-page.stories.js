import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import EventPageTwig from "./event-page.twig";
import './event-page.scss';

export default {
  title: "Design System/Templates/Event Page",
};

const Template = ({ attributes, title, event_details, related, content }) =>
  EventPageTwig({
    attributes,
    title,
    event_details,
    related,
    content
  });

export const EventPage = Template.bind({});
EventPage.args = {
  attributes: new DrupalAttributes(),
  title: 'Event',
  event_details: 'event details..',
  related: 'Related info',
  content: 'event content..'
};
