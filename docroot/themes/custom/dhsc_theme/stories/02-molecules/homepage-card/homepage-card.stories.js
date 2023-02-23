import homepageCard from "./homepage-card.twig";

export default {
  title: "Design System/Molecules/Homepage Card",
};

import image from '../../assets/homepage-card.jpg';
const imgTag = `<img src=${image} alt='Digital Social Care'/>`

const Template = ({ image, link, heading, description }) =>
  homepageCard({
    image,
    link,
    heading,
    description,
  });

export const HomepageCard = Template.bind({});
HomepageCard.args = {
  image: imgTag,
  link: "https://www.digitalsocialcare.co.uk/",
  heading: "Get help using technology at your organisation",
  description: "Description goes here",
};
