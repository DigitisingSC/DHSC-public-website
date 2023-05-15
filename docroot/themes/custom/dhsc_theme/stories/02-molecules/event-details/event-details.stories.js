import React from 'react';
import DrupalAttribute from '../../../.storybook/drupalAttributes';
import eventDetails from "./event-details.twig";

export default {
  title: "Design System/Molecules/Event details",
};

const Template = ({ date, start_time, end_time, link }) =>
  eventDetails({
    date,
    start_time,
    end_time,
    link,
  });

export const EventDetails = Template.bind({});
EventDetails.args = {
  date: "03 May, 2023",
  start_time: "10:00 am",
  end_time: "12:00 pm",
  link: "https://www.digitalsocialcare.co.uk/",
};
