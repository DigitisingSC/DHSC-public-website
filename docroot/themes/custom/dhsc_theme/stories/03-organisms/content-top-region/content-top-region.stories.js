import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import contentTopRegionTwig from "./content-top-region.twig";
import { Alert } from '../../02-molecules/alert/alert.stories';
import AlertTwig from '../../02-molecules/alert/alert.twig';

export default {
  title: "Design System/Organisms/Content Top Region",
};

const Template = ({ attributes, content }) =>
  contentTopRegionTwig({
    attributes,
    content,
  });

const AlertTemplate = (args) => AlertTwig({
  ...Alert.args,
});

export const ContentTopRegion = Template.bind({});
ContentTopRegion.args = {
  attributes: new DrupalAttributes(),
  content: { AlertTemplate }
}
