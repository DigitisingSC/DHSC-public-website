import svgIcon from "./svg-icon.twig";
import drupalAttribute from 'drupal-attribute';
import icon from "./icons/file.svg";


export default {
  title: "Design System/Atoms/SVG Icon",
};

const attributes =  new drupalAttribute();

const Template = ({ attributes, icon, classes }) =>
  svgIcon({
    attributes,
    icon,
    classes
  });

export const svgIconHTML = Template.bind({});
svgIconHTML.args = {
  icon: icon,
  classes: 'classes',
  attributes: attributes
};
