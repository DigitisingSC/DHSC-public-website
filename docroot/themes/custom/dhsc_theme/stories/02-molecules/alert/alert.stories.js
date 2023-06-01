import alert from './alert.twig';
import './alert.scss';

export default {
  title: "Design System/Molecules/Alert",
  argTypes: {
    text: { control: 'text' },
    alertType: {
      control: { type: 'select'},
      options: ['primary', 'secondary', 'warning', 'success', 'price']
    },
  },
};

const Template = ({ text, ...args }) =>
    alert({
      text,
      ...args,
    });

export const Primary = Template.bind({});
Primary.args = {
  alertType: 'primary',
  text: 'An example alert with an icon',
};

export const Secondary = Template.bind({});
Secondary.args = {
  alertType: 'secondary',
  text: 'An example alert with an icon',
};

export const Warning = Template.bind({});
Warning.args = {
  alertType: 'warning',
  text: 'An example alert with an icon',
};

export const Success = Template.bind({});
Success.args = {
  alertType: 'success',
  text: 'An example alert with an icon',
};

export const Price = Template.bind({});
Price.args = {
  alertType: 'price',
  text: 'An example alert with an icon',
};
