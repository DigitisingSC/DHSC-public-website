document.addEventListener('alpine:init', () => {
  Alpine.data('feedbackWidget', () => ({
    state: 'initial',
    form: {
      feedback_type: '',
      page_url: '',
      suggestion: '',
      context: '',
      description: '',
    },

    init() {
      // Set the page URL when component initialises
      this.form.page_url = window.location.href;
    },

    setFeedback(type) {
      // Set the feedback type and update state
      this.form.feedback_type = type;
      this.state = type;
    },

    reset() {
      // Reset the form and state to initial
      this.state = 'initial';
      this.form.suggestion = '';
      this.form.context = '';
      this.form.description = '';
    },
    submitWithType(type) {
      this.setFeedback(type);
      this.submit();
    },
    submit() {
      console.log(JSON.stringify(this.form));

      // Fetch CSRF token first
      fetch('/session/token')
        .then((response) => response.text())
        .then((token) => {
          console.log('Token:', token);
          // Use the token to submit feedback
          return fetch('/dhsc-feedback/submit', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-Token': token,
            },
            body: JSON.stringify(this.form),
          });
        })
        .then((response) => {
          console.log(response);
          // Check if the response is ok (status 200-299)
          if (!response.ok) {
            // If not ok, extract the error message
            return response.text().then((text) => {
              throw new Error(
                `Server responded with status ${response.status}: ${text}`
              );
            });
          }
          // Parse response as JSON if successful
          return response.json();
        })
        .then((data) => {
          console.log('Feedback submitted', data);
          this.state = 'submitted';
        })
        .catch((error) => {
          console.error('Error submitting feedback', error);
        });
    },
  }));
});
