import React from 'react';
import DrupalAttribute from '../../../.storybook/drupalAttributes';
import layout from "./layout.twig";

import { Header } from "../../03-organisms/header/header.stories";
import HeaderTwig from "../../03-organisms/header/header.twig";
import { Navigation } from "../../03-organisms/navigation/navigation.stories";
import NavigationTwig from "../../03-organisms/navigation/navigation.twig";
import { Footer } from "../../03-organisms/footer/footer.stories";
import FooterTwig from "../../03-organisms/footer/footer.twig";

export default {
  title: "Design System/Templates/Layout",
};

const Template = ({
  header,
  search,
  mobile_search,
  primary_menu,
  secondary_menu,
  banner,
  tabs,
  breadcrumb,
  messages,
  content_top,
  content,
  content_bottom,
  sidebar_first,
  sidebar_second,
  footer_first,
  footer_second,
  footer_third,
  footer,
  lower_footer_first,
  lower_footer_second,
  lower_footer_third,
  disabled
}) =>
  layout({
    header,
    search,
    mobile_search,
    primary_menu,
    secondary_menu,
    banner,
    tabs,
    breadcrumb,
    messages,
    content_top,
    content,
    content_bottom,
    sidebar_first,
    sidebar_second,
    footer_first,
    footer_second,
    footer_third,
    footer,
    lower_footer_first,
    lower_footer_second,
    lower_footer_third,
    disabled
  });

const HeaderTemplate = (args) => HeaderTwig({
  ...Header.args
});
const NavigationTemplate = (args) => NavigationTwig({
  ...Navigation.args
});

const FooterTemplate = (args) => FooterTwig({
  ...Footer.args
});

export const Layout = Template.bind({});
Layout.args = {
  tabs: "Tabs",
  header: HeaderTemplate,
  search: "Search",
  mobile_search: "Mobile search",
  primary_menu: NavigationTemplate,
  secondary_menu: "Secondary menu",
  banner: "Banner",
  breadcrumb: "Breadcrumb",
  messages: "Messages",
  content_top: "Content top",
  content: "Content",
  content_bottom: "Content bottom",
  sidebar_first: "Sidebar first",
  sidebar_second: "Sidebar second",
  footer_first: "Footer first",
  footer_second: "Footer second",
  footer_third: "Footer third",
  footer: FooterTemplate,
  lower_footer_first: "Lower footer first",
  lower_footer_second: "Lower footer second",
  lower_footer_third: "Lower footer third",
  disabled: "Disabled"
};
