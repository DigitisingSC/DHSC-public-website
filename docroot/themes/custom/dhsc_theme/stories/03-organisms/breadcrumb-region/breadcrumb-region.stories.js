import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import breadcrumbRegion from "./breadcrumb-region.twig";
import { Breadcrumbs } from "../../02-molecules/breadcrumbs/breadcrumbs.stories";
import BreadcrumbsTwig from "../../02-molecules/breadcrumbs/breadcrumbs.twig";

export default {
  title: "Design System/Organisms/Breadcrumb Region",
};

const Template = ({ attributes, content}) =>
  breadcrumbRegion({
    attributes,
    content
  });

const BreadcrumbsTemplate = (args) => BreadcrumbsTwig({
  ...Breadcrumbs.args
});


export const BreadcrumbRegion = Template.bind({});
BreadcrumbRegion.args = {
  attributes: new DrupalAttributes(),
  content: BreadcrumbsTemplate
};
