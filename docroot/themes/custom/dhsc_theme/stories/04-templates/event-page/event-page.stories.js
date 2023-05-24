import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import eventPage from "./event-page.twig";

export default {
  title: "Design System/Templates/Event page",
};

const Template = ({ title, event_details, content }) =>
  eventPage({
    title,
    event_details,
    content
  });

export const EventPage = Template.bind({});
EventPage.args = {
  title: "Event",
  event_details: "event details..",
  content: "event content.."
};
