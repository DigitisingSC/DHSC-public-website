import header from "./header.twig";

export default {
  title: "Design System/Organisms/Header",
};

const Template = ({title, content}) =>
  header({
    title,
    content
  });

export const Header = Template.bind({});
Header.args = {
  title: 'Header region',
  content: 'Content of the region'
}
