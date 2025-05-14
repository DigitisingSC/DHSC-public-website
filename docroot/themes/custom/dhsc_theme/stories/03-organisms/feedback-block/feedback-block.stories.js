import DrupalAttribute from 'drupal-attribute';
import feedbackBlock from './feedback-block.twig';
import './feedback-block.scss';
import './feedback-block.js';

export default {
  title: "Design System/Organisms/Feedback Block",
  parameters: {
    layout: 'fullscreen',
  },
};

const Template = ({ ...args }) => feedbackBlock(args);

export const Initial = Template.bind({});
Initial.args = {
  attributes: {},
  current_url: 'https://example.com/page',
  state: 'initial',
  question_text: 'Is this page useful?',
  yes_text: 'Yes',
  no_text: 'No',
  problem_link_text: 'Report a problem with this page',
};

export const NoFeedback = Template.bind({});
NoFeedback.args = {
  ...Initial.args,
  state: 'no',
  improvement_label: 'How can we improve this page?',
};

export const ProblemFeedback = Template.bind({});
ProblemFeedback.args = {
  ...Initial.args,
  state: 'problem',
  context_label: 'What were you doing?',
  description_label: 'What went wrong?',
};

export const Submitted = Template.bind({});
Submitted.args = {
  ...Initial.args,
  state: 'submitted',
  submitted_message: 'Thanks for letting us know!',
};
