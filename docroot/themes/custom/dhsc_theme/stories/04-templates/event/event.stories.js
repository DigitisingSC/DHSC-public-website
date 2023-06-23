import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import event from "./event.twig";

export default {
  title: "Design System/Templates/Event",
};

const Template = ({ title, event_details, content }) =>
  event({
    title,
    event_details,
    content
  });

export const Event = Template.bind({});
Event.args = {
  title: "Event",
  event_details: "event details..",
  content: "event content.."
};
