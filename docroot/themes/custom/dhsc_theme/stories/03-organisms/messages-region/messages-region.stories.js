import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import messagesRegion from "./messages-region.twig";

export default {
  title: "Design System/Organisms/Messages Region",
};

const Template = ({ attributes, content }) =>
  messagesRegion({
    attributes,
    content
  });

export const MessagesRegion = Template.bind({});
MessagesRegion.args = {
  attributes: new DrupalAttributes(),
  content: 'Messages'
}
