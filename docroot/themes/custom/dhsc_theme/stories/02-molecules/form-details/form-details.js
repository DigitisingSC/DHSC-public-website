function findSelectedItems () {
  const checkCheckboxes = () => {
    document.querySelectorAll(".m-form-details").forEach(details => {
      const noChecked = details.querySelectorAll("input[type='checkbox']:checked").length;
      const selected = details.querySelector(".m-form-details__selected");
      if (noChecked > 0 ) {
        // show selected
        const counter = details.querySelector(".m-form-details__counter");
        if (counter) {
          counter.innerHTML = noChecked;
        }
        // with numbers
        selected.classList.remove('hidden')
      } else {
        // hide selected
        selected.classList.add('hidden')
      }
    })
  }

  checkCheckboxes()

  document.querySelectorAll(".m-form-details input[type='checkbox']").forEach(checkbox => {
    checkbox.addEventListener("change", checkCheckboxes);
  })
}

document.addEventListener("DOMContentLoaded", findSelectedItems);
