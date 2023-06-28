import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import footerContactTwig from './footer-contact.twig';
import './footer-contact.scss';

export default {
  title: "Design System/Molecules/Footer contact",
};

const Template = ({ attributes, link, phone }) =>
  footerContactTwig({
    attributes,
    link,
    phone
  });

export const FooterContact = Template.bind({});
FooterContact.args = {
  attributes: new DrupalAttributes(),
  link: '<a href="/link">Sign up to our newsletter</a>',
  phone: '000 123 456'
};
