import casestudyPage from "./casestudy-page.twig";

export default {
  title: "Design System/Templates/Case study page",
};

const Template = ({ content }) =>
  casestudyPage({
    content
  });

export const casestudyPageHTML = Template.bind({});
casestudyPageHTML.args = {
  content: "casestudy page.."
};
