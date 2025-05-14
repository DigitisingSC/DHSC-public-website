window.feedbackWidget = function () {
  // This script is used to allow the feedback component to be viewed / interacted with in storybook.
  // Ihe script isn't required on the main site frontend.
  return {
    state: 'initial',
    pageUrl: '',
    form: {
      feedback_type: '',
      suggestion: '',
      context: '',
      description: '',
    },
    init(url) {
      this.pageUrl = url;
    },
    setFeedback(type) {
      this.form.feedback_type = type;
      this.state = type;
    },
    submit() {
      console.log('Submitting feedback:', this.form);
      this.state = 'submitted';
    },
    reset() {
      this.state = 'initial';
      this.form = {
        feedback_type: '',
        suggestion: '',
        context: '',
        description: '',
      };
    },
  };
};
