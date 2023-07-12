import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import eventDetailsDateTwig from "./event-details--date.twig";
import eventDetailsTimeTwig from "./event-details--time.twig";
import './event-details.scss';

export default {
  title: "Design System/Molecules/Event details",
};

const eventDetailsDateTemplate = ({ date, end_date, start_time, end_time, expired, location, link }) =>
  eventDetailsDateTwig({
    date,
    end_date,
    start_time,
    end_time,
    expired,
    location,
    link,
  });

const eventDetailsTimeTemplate = ({ date, end_date, start_time, end_time, expired, location, link }) =>
  eventDetailsTimeTwig({
    date,
    end_date,
    start_time,
    end_time,
    expired,
    location,
    link,
  });

export const EventDetailsDate = eventDetailsDateTemplate.bind({});
EventDetailsDate.args = {
  attributes: new DrupalAttributes(),
  date: "27 July 2023",
  end_date: "28 July 2023",
  start_time: "10:00",
  end_time: "14:00",
  expired: false,
  location:'123 avenue road, The place, Townsville, SE1 123',
  link: "https://www.digitalsocialcare.co.uk/",
};

export const EventDetailsExpiredDate = eventDetailsDateTemplate.bind({});
EventDetailsExpiredDate.args = {
  attributes: new DrupalAttributes(),
  date: "01 July 2023",
  end_date: "01 July 2023",
  start_time: "10:00",
  end_time: "14:00",
  expired: true,
  location:'123 avenue road, The place, Townsville, SE1 123',
  link: "https://www.digitalsocialcare.co.uk/",
};

export const EventDetailsTime = eventDetailsTimeTemplate.bind({});
EventDetailsTime.args = {
  attributes: new DrupalAttributes(),
  date: "27 July 2023",
  end_date : "28 July 2023",
  start_time: "10:00",
  end_time: "14:00",
  expired: false,
  location:'123 avenue road, The place, Townsville, SE1 123',
  link: "https://www.digitalsocialcare.co.uk/",
};
