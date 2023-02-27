import banner from "./banner.twig";

export default {
  title: "Design System/Atoms/Banner",
};

import image from '../../../assets/images/banner.jpg';
const imgTag = `<img src=${image} alt='Digital Social Care Banner'/>`

const Template = ({ image }) =>
  banner({
    image,
  });

export const banner = Template.bind({});
banner.args = {
  image: imgTag,
};
