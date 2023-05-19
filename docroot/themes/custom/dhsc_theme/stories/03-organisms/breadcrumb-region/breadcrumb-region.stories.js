import React from 'react';
import DrupalAttribute from '../../../.storybook/drupalAttributes';
import breadcrumbRegion from "./breadcrumb-region.twig";
import { Breadcrumbs } from "../../02-molecules/breadcrumbs/breadcrumbs.stories";
import BreadcrumbsTwig from "../../02-molecules/breadcrumbs/breadcrumbs.twig";

export default {
  title: "Design System/Organisms/Breadcrumb Region",
};

const Template = ({ content}) =>
  breadcrumbRegion({
    content
  });

const BreadcrumbsTemplate = (args) => BreadcrumbsTwig({
  ...Breadcrumbs.args
});


export const BreadcrumbRegion = Template.bind({});
BreadcrumbRegion.args = {
  content: BreadcrumbsTemplate
};
