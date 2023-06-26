import 'regenerator-runtime/runtime';
import Alpine from 'alpinejs';

export const accordionItem = () => {
  Alpine.data('accordionItem', () => ({
    $accordionItem: false,
    init() {
      this.$accordionItem = this.$el;
    },
  }));
};
