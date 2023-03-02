import prevNextButtons from "./prev-next-buttons.twig";

import './../../../../../custom/dhsc_theme/css/components/prev-next.css';

const next_icon =
  '<div aria-hidden="true" class="lgd-icon lgd-prev-next__icon lgd-prev-next__icon--next">\
      <svg xmlns="http://www.w3.org/2000/svg" focusable="false" viewBox="0 0 320 512">\
      <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z">\
      </svg>\
    </div>';

const prev_icon =
  '<div aria-hidden="true" class="lgd-icon lgd-prev-next__icon lgd-prev-next__icon--prev">\
      <svg xmlns="http://www.w3.org/2000/svg" focusable="false" viewBox="0 0 320 512">\
      <path d="M34.52 239.03L228.87 44.69c9.37-9.37 24.57-9.37 33.94 0l22.67 22.67c9.36 9.36 9.37 24.52.04 33.9L131.49 256l154.02 154.75c9.34 9.38 9.32 24.54-.04 33.9l-22.67 22.67c-9.37 9.37-24.57 9.37-33.94 0L34.52 272.97c-9.37-9.37-9.37-24.57 0-33.94z"></path>\
      </svg>\
    </div>';


export default {
  title: "Design System/Molecules/Previous Next Buttons",
};

const Template = ({ prev_icon, next_icon, prev_url, next_url, prev_title, next_title, prev_button_label, next_button_label }) =>
  prevNextButtons({
    prev_icon,
    next_icon,
    prev_url,
    next_url,
    prev_title,
    next_title,
    prev_button_label,
    next_button_label
  });

export const PrevNextButtonsHTML = Template.bind({});
PrevNextButtonsHTML.args = {
  prev_icon: prev_icon,
  next_icon: next_icon,
  prev_url: "#",
  next_url: "#",
  prev_title: "Guide page 0",
  next_title: "Guide page 1",
  prev_button_label: "Previous",
  next_button_label: "Next",
};
