import prevButton from "./prev-button.twig";

import './../../../../../custom/dhsc_theme/css/components/prev-next.css';

const icon_markup =
  '<div aria-hidden="true" class="lgd-icon lgd-prev-next__icon lgd-prev-next__icon--prev">\
      <svg xmlns="http://www.w3.org/2000/svg" focusable="false" viewBox="0 0 320 512">\
      <path d="M34.52 239.03L228.87 44.69c9.37-9.37 24.57-9.37 33.94 0l22.67 22.67c9.36 9.36 9.37 24.52.04 33.9L131.49 256l154.02 154.75c9.34 9.38 9.32 24.54-.04 33.9l-22.67 22.67c-9.37 9.37-24.57 9.37-33.94 0L34.52 272.97c-9.37-9.37-9.37-24.57 0-33.94z"></path>\
      </svg>\
    </div>';

export default {
  title: "Design System/Atoms/Previous button",
};

const Template = ({ icon, previous_url, previous_title, button_label }) =>
  prevButton({
    icon,
    previous_url,
    previous_title,
    button_label
  });

export const PrevButtonHTML = Template.bind({});
PrevButtonHTML.args = {
  icon: icon_markup,
  previous_url: "#",
  previous_title: "Guide page 0",
  button_label: "Previous",
};
