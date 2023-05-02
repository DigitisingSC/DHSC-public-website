import { create } from '@storybook/theming';
import Logo from '../stories/assets/logo.svg';
export default create({
  base: 'light',
  brandTitle: 'DSC',
  brandUrl: '/',
  brandImage: Logo,
  brandTarget: '_self',
  appBg: '#F5F5F5',
});
