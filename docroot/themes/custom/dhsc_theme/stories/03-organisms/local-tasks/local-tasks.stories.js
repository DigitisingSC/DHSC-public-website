import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import localTasks from './local-tasks.twig';
import './local-tasks.scss';
export default {
  title: "Design System/Organisms/Local tasks",
};

const Template = ({ attributes, content }) =>
  localTasks({
    attributes,
    content
  });

export const LocalTasks = Template.bind({});
LocalTasks.args = {
  attributes: new DrupalAttributes(),
  content: 'Local tasks',
}
