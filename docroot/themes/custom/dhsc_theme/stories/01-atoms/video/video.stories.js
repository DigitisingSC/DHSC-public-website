import video from "./video.twig";

export default {
  title: "Design System/Atoms/Video",
};

const Template = ({ title }) =>
  video({
    title,
  });

export const videoHTML = Template.bind({});
videoHTML.args = {
  title: "Video title..",
};
