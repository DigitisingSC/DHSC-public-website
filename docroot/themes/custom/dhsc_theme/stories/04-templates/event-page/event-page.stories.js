import React from 'react';
import DrupalAttribute from '../../../.storybook/drupalAttributes';
import eventPage from "./event-page.twig";

export default {
  title: "Design System/Templates/Event page",
};

const Template = ({ event_details, content }) =>
  eventPage({
    event_details,
    content
  });

export const EventPage = Template.bind({});
EventPage.args = {
  event_details: "event details..",
  content: "event page.."
};
