import contentCard from "./content-card.twig";
export default {
  title: "Design System/Molecules/Content Card",
};

import image from '../../assets/images/content-card.jpg';
const imgTag = `<img src=${image} alt='Digital Social Care'/>`

const Template = ({ image, link, heading, description }) =>
  contentCard({
    image,
    link,
    heading,
    description,
  });

export const ContentCard = Template.bind({});
ContentCard.args = {
  image: imgTag,
  link: "https://www.digitalsocialcare.co.uk/",
  heading: "Get help using technology at your organisation",
  description: "Description goes here",
};
