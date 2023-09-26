import React from 'react';
import DrupalAttributes from '../../../.storybook/drupalAttributes';
import supplierPage from "./supplier-page.twig";

import './supplier-page.scss';

export default {
  title: "Design System/Templates/Supplier page",
};

import image from '../../assets/images/supplier-image.png';
const imgTag = `<div><img src=${image} alt='Digital Social Care'/></div>`

const Template = ({ attributes, title, list_price, summary, image, content, last_updated }) =>
  supplierPage({
    attributes,
    title,
    list_price,
    summary,
    image,
    content,
    last_updated
  });

export const SupplierPage= Template.bind({});
SupplierPage.args = {
  attributes: new DrupalAttributes(),
  title: "Using technology for a seamless recruitment process",
  list_price: "Coming soon",
  summary: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam eu turpis molestie, dictum est a, mattis tellus.',
  image: imgTag,
  content: `<h2>Nulla neque dolor sagittis eget</h2>
  <p>Suspendisse eu ligula. Ut tincidunt tincidunt erat. Aenean commodo ligula eget dolor. Aliquam erat volutpat. Nunc sed turpis. Phasellus tempus. Aenean vulputate eleifend tellus. In ut quam vitae odio lacinia tincidunt. Etiam ut purus mattis mauris sodales aliquam. Praesent ut ligula non mi varius sagittis. Phasellus volutpat, metus eget egestas mollis, lacus lacus blandit dui, id egestas quam mauris ut lacus. Aenean vulputate eleifend tellus. Curabitur blandit mollis lacus. Mauris sollicitudin fermentum libero. Maecenas nec odio et ante tincidunt tempus.</p>
  <h3>Suspendisse nisl elit rhoncus eget</h3>
  <p>Praesent ac massa at ligula laoreet iaculis. Praesent adipiscing. Fusce neque. Sed cursus turpis vitae tortor. Etiam iaculis nunc ac metus. Phasellus consectetuer vestibulum elit. Ut tincidunt tincidunt erat. Vivamus in erat ut urna cursus vestibulum. Praesent congue erat at massa. Sed fringilla mauris sit amet nibh.</p>`,
  last_updated: '05 June 2023'
};
