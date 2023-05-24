import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import layout from "./layout.twig";

import { Header } from "../../03-organisms/header/header.stories";
import HeaderTwig from "../../03-organisms/header/header.twig";
import { Navigation } from "../../03-organisms/navigation/navigation.stories";
import NavigationTwig from "../../03-organisms/navigation/navigation.twig";
import { BannerRegion } from "../../03-organisms/banner-region/banner-region.stories";
import BannerRegionTwig from "../../03-organisms/banner-region/banner-region.twig";
import { BreadcrumbRegion } from "../../03-organisms/breadcrumb-region/breadcrumb-region.stories";
import breadcrumbRegionTwig from "../../03-organisms/breadcrumb-region/breadcrumb-region.twig";
import { LocalTasks } from "../../03-organisms/local-tasks/local-tasks.stories";
import localTasksRegionTwig from "../../03-organisms/local-tasks/local-tasks.twig";
import { MessagesRegion } from "../../03-organisms/messages-region/messages-region.stories";
import messagesRegionTwig from "../../03-organisms/messages-region/messages-region.twig";
import {
  Footer,
  FooterFirst,
  FooterSecond,
  FooterThird,
  LowerFooterFirst,
  LowerFooterSecond,
  LowerFooterThird
} from "../../03-organisms/footer/footer.stories";
import FooterFirstTwig from "../../03-organisms/footer/footer-first.twig";
import FooterSecondTwig from "../../03-organisms/footer/footer-second.twig";
import FooterThirdTwig from "../../03-organisms/footer/footer-third.twig";
import FooterTwig from "../../03-organisms/footer/footer.twig";
import LowerFooterFirstTwig from "../../03-organisms/footer/lower-footer-first.twig";
import LowerFooterSecondTwig from "../../03-organisms/footer/lower-footer-second.twig";
import LowerFooterThirdTwig from "../../03-organisms/footer/lower-footer-third.twig";

export default {
  title: "Design System/Templates/Layout",
};

const Template = ({
  has_sidebars,
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
    has_sidebars,
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
const BannerRegionTemplate = (args) => BannerRegionTwig({
  ...BannerRegion.args
});
const BreadcrumbRegionTemplate = (args) => breadcrumbRegionTwig({
  ...BreadcrumbRegion.args
});
const MessagesRegionTemplate = (args) => messagesRegionTwig({
  ...MessagesRegion.args
});
const LocalTasksRegionTemplate = (args) => localTasksRegionTwig({
  ...LocalTasks.args
});
const FooterFirstTemplate = (args) => FooterFirstTwig({
  ...FooterFirst.args
});
const FooterSecondTemplate = (args) => FooterSecondTwig({
  ...FooterSecond.args
});
const FooterThirdTemplate = (args) => FooterThirdTwig({
  ...FooterThird.args
});
const FooterTemplate = (args) => FooterTwig({
  ...Footer.args
});
const LowerFooterFirstTemplate = (args) => LowerFooterFirstTwig({
  ...LowerFooterFirst.args
});
const LowerFooterSecondTemplate = (args) => LowerFooterSecondTwig({
  ...LowerFooterSecond.args
});
const LowerFooterThirdTemplate = (args) => LowerFooterThirdTwig({
  ...LowerFooterThird.args
});

export const Layout = Template.bind({});
Layout.args = {
  has_sidebars: true,
  header: HeaderTemplate,
  search: "Search",
  mobile_search: "Mobile search",
  primary_menu: NavigationTemplate,
  secondary_menu: "Secondary menu",
  banner: BannerRegionTemplate,
  breadcrumb: BreadcrumbRegionTemplate,
  messages: MessagesRegionTemplate,
  tabs: LocalTasksRegionTemplate,
  content_top: "Content top",
  content: "Content",
  content_bottom: "Content bottom",
  sidebar_first: "Sidebar first",
  sidebar_second: "Sidebar second",
  footer_first: FooterFirstTemplate,
  footer_second: FooterSecondTemplate,
  footer_third: FooterThirdTemplate,
  footer: FooterTemplate,
  lower_footer_first: LowerFooterFirstTemplate,
  lower_footer_second: LowerFooterSecondTemplate,
  lower_footer_third: LowerFooterThirdTemplate,
  disabled: "Disabled"
};
