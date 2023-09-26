import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import messagesRegionTwig from "./messages-region.twig";

export default {
  title: "Design System/Organisms/Messages Region",
};

const Template = ({ attributes, content }) =>
  messagesRegionTwig({
    attributes,
    content,
  });

export const MessagesRegion = Template.bind({});
MessagesRegion.args = {
  attributes: new DrupalAttributes(),
  content: ''
}
