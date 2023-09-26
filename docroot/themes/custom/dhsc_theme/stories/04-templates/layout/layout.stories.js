import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import layout from "./layout.twig";

import { Header } from "../../03-organisms/header/header.stories";
import HeaderTwig from "../../03-organisms/header/header.twig";
import { SearchRegion } from "../../03-organisms/search-region/search-region.stories";
import SearchRegionTwig from "../../03-organisms/search-region/search-region.twig";
import { NavigationPrimary } from "../../03-organisms/navigation/navigation.stories";
import NavigationPrimaryTwig from "../../03-organisms/navigation/navigation--primary.twig";
import { NavigationSecondary } from "../../03-organisms/navigation/navigation.stories";
import NavigationSecondaryTwig from "../../03-organisms/navigation/navigation--secondary.twig";
import { MessagesRegion } from "../../03-organisms/messages-region/messages-region.stories";
import messagesRegionTwig from "../../03-organisms/messages-region/messages-region.twig";
import { ContentTopRegion } from '../../03-organisms/content-top-region/content-top-region.stories';
import ContentTopRegionTwig from '../../03-organisms/content-top-region/content-top-region.twig';
import { BannerRegion } from "../../03-organisms/banner-region/banner-region.stories";
import BannerRegionTwig from "../../03-organisms/banner-region/banner-region.twig";
import { BreadcrumbRegion } from "../../03-organisms/breadcrumb-region/breadcrumb-region.stories";
import breadcrumbRegionTwig from "../../03-organisms/breadcrumb-region/breadcrumb-region.twig";
import { LocalTasks } from "../../03-organisms/local-tasks/local-tasks.stories";
import localTasksRegionTwig from "../../03-organisms/local-tasks/local-tasks.twig";
import {
  FooterTop,
  Footer,
  FooterBottom,
  FooterLegal,
} from "../../03-organisms/footer/footer.stories";
import FooterTopTwig from "../../03-organisms/footer/footer-top.twig";
import FooterTwig from "../../03-organisms/footer/footer.twig";
import FooterBottomTwig from "../../03-organisms/footer/footer-bottom.twig";
import FooterLegalTwig from "../../03-organisms/footer/footer-legal.twig";

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
  messages,
  banner,
  tabs,
  breadcrumb,
  content_top,
  content,
  content_bottom,
  sidebar_first,
  sidebar_second,
  footer_top,
  footer,
  footer_bottom,
  footer_legal,
  disabled,
  remove_main_id
}) =>
  layout({
    has_sidebars,
    header,
    search,
    mobile_search,
    primary_menu,
    secondary_menu,
    messages,
    banner,
    tabs,
    breadcrumb,
    content_top,
    content,
    content_bottom,
    sidebar_first,
    sidebar_second,
    footer_top,
    footer,
    footer_bottom,
    footer_legal,
    disabled,
    remove_main_id,
  });

const HeaderTemplate = (args) => HeaderTwig({
  ...Header.args
});
const SearchRegionTemplate = (args) => SearchRegionTwig({
  ...SearchRegion.args
});
const NavigationPrimaryTemplate = (args) => NavigationPrimaryTwig({
  ...NavigationPrimary.args
});
const NavigationSecondaryTemplate = (args) => NavigationSecondaryTwig({
  ...NavigationSecondary.args
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
const ContentTopRegionTemplate = (args) => ContentTopRegionTwig({
  ...ContentTopRegion.args
});
const LocalTasksRegionTemplate = (args) => localTasksRegionTwig({
  ...LocalTasks.args
});
const FooterTopTemplate = (args) => FooterTopTwig({
  ...FooterTop.args
});

const FooterTemplate = (args) => FooterTwig({
  ...Footer.args
});
const FooterBottomTemplate = (args) => FooterBottomTwig({
  ...FooterBottom.args
});
const FooterLegalTemplate = (args) => FooterLegalTwig({
  ...FooterLegal.args
});

export const Layout = Template.bind({});
Layout.args = {
  has_sidebars: true,
  header: HeaderTemplate,
  search: SearchRegionTemplate,
  mobile_search: "Mobile search",
  primary_menu: NavigationPrimaryTemplate,
  secondary_menu: NavigationSecondaryTemplate,
  banner: BannerRegionTemplate,
  breadcrumb: BreadcrumbRegionTemplate,
  messages: MessagesRegionTemplate,
  tabs: LocalTasksRegionTemplate,
  content_top: ContentTopRegionTemplate,
  content: "Content",
  content_bottom: "Content bottom",
  sidebar_first: "Sidebar first",
  sidebar_second: "Sidebar second",
  footer_top: FooterTopTemplate,
  footer: FooterTemplate,
  footer_bottom: FooterBottomTemplate,
  footer_legal: FooterLegalTemplate,
  disabled: "Disabled",
  remove_main_id: false,
};
