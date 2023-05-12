import footer from "./footer.twig";

export default {
  title: "Design System/Organisms/Footer",
};

const Template = ({ content}) =>
  footer({
    content
  });

export const Footer = Template.bind({});
Footer.args = {
  content: "Content of the region"
};
