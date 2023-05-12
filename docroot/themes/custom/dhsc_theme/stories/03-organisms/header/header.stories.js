import header from "./header.twig";

export default {
  title: "Design System/Organisms/Header",
};

const Template = ({ content}) =>
  header({
    content
  });

export const Header = Template.bind({});
Header.args = {
  content: 'Content of the region'
}
