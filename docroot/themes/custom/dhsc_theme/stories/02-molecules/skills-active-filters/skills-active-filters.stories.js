import React from 'react';
import skillsActiveFilters from "./skills-active-filters.twig";
import './skills-active-filters.scss';


export default {
  title: "Design System/Molecules/Skills active filters",
};

const SkillsActiveFiltersHtml = ({ links }) =>
  skillsActiveFilters({
    links,
  });


export const SkillsActiveFilters = SkillsActiveFiltersHtml.bind({});
SkillsActiveFilters.args = {
  links: [
    {
      target: '#1',
      title: "Filter",
    },
    {
      target: '#1',
      title: "Filter with a much longer title",
    },
    {
      target: '#1',
      title: "Other filter",
    },
  ],
};
