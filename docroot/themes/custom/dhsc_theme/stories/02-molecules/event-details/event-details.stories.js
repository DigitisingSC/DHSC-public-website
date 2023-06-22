import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import eventDetailsDateTwig from "./event-details--date.twig";
import eventDetailsTimeTwig from "./event-details--time.twig";

export default {
  title: "Design System/Molecules/Event details",
};

const eventDetailsDateTemplate = ({ date, end_date, start_time, end_time, link }) =>
  eventDetailsDateTwig({
    date,
    end_date,
    start_time,
    end_time,
    link,
  });

const eventDetailsTimeTemplate = ({ date, end_date, start_time, end_time, link }) =>
  eventDetailsTimeTwig({
    date,
    end_date,
    start_time,
    end_time,
    link,
  });

export const EventDetailsDate = eventDetailsDateTemplate.bind({});
EventDetailsDate.args = {
  attributes: new DrupalAttributes(),
  date: "27 July 2023",
  end_date: "28 July 2023",
  start_time: "10:00",
  end_time: "14:00",
  link: "https://www.digitalsocialcare.co.uk/",
};

export const EventDetailsTime = eventDetailsTimeTemplate.bind({});
EventDetailsTime.args = {
  attributes: new DrupalAttributes(),
  date: "27 July 2023",
  end_date : "28 July 2023",
  start_time: "10:00",
  end_time: "14:00",
  link: "https://www.digitalsocialcare.co.uk/",
};
