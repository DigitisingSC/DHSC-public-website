import footer from "./footer.twig";

export default {
  title: "Design System/Organisms/Footer",
};

const Template = ({ title, content}) =>
  footer({
    title,
    content
  });

export const Footer = Template.bind({});
Footer.args = {
  title: "Footer region",
  content: "Content of teh region"
};
