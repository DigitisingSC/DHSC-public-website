import banner from "./banner.twig";

import './../../../../../custom/dhsc_theme/src/localgov/components/banner.css';

export default {
  title: "Design System/Molecules/Banner",
};

import image from '../../../assets/images/banner.jpg';
const imgTag = `<img src=${image} alt='Digital Social Care Banner'/>`

const Template = ({ image, url, heading, text }) =>
  banner({
    image,
    url,
    heading,
    text,
  });

export const bannerHTML = Template.bind({});
bannerHTML.args = {
  image: imgTag,
  url: "https://www.digitalsocialcare.co.uk/",
  heading: "Banner content",
  text: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua",
};
