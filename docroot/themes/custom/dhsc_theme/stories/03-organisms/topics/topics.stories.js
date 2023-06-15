import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import topicsTwig from "./topics.twig";
import './topics.scss';

import { Topic } from "../../02-molecules/topic/topic.stories.js";
import topicTwig from "../../02-molecules/topic/topic.twig";

export default {
  title: "Design System/Organisms/Topics",
};

const topicTemplage = (args) => topicTwig({
  ...Topic.args,
});

const topicTemplage2 = (args) => topicTwig({
  ...Topic.args,
});

const topicTemplage3 = (args) => topicTwig({
  ...Topic.args,
});

const topicTemplage4 = (args) => topicTwig({
  ...Topic.args,
});

const topicTemplage5 = (args) => topicTwig({
  ...Topic.args,
});

const topicTemplage6 = (args) => topicTwig({
  ...Topic.args,
});

const topicsTemplate = ({ attributes, title, items, link }) =>
  topicsTwig({
    attributes,
    title,
    items
  });

export const Topics = topicsTemplate.bind({});

Topics.args = {
  attributes: new DrupalAttributes(),
  title: 'Main topics',
  items: { topicTemplage, topicTemplage2, topicTemplage3, topicTemplage4, topicTemplage5, topicTemplage6 },
}
