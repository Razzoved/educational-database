if (!secureFetch) {
  console.error("AJAX calls will fail, no FETCH.js provided!");
} else {
  console.debug("Loaded modal.js");
}

HTMLElement.prototype.reapplyScripts = function () {
  scripts = Array.from(this.querySelectorAll("script")).forEach((e) => {
    const script = document.createElement("script");
    script.text = e.innerText;
    e.remove();
    this.appendChild(script);
  });
};

/**
 * Closes the modal window. If modalId is different from defaut,
 * only hides it.
 *
 * @param {Event} event    null or event with target
 * @param {string} modalId id of modal to close
 */
const modalClose = function (event = null, modalId = "modal") {
  const modal = document.getElementById(modalId);
  if (modal && (event === null || event.target === modal)) {
    if (modalId !== "modal") {
      modal.querySelector("#error")?.remove();
      modal.classList.remove("modal--visible");
    } else {
      modal.remove();
    }
  }
};

/**
 * Sends a request to the server for a modal to be opened.
 * Shows the result if response is HTTP_OK.
 *
 * @param {string} url      address to send request to
 * @param {string} template html in form of string, contains placeholders
 */
const modalOpen = async (url, template) => {
  try {
    if (url) {
      const response = await secureFetch(url, {});
      if (!response.ok) {
        throw new Error(response.statusText);
      }
      template = template.fill({ ...(await response.json()) });
    }
    const element = document.body.appendChild(template.html());
    element.reapplyScripts();
    element.classList.add("modal--visible");
  } catch (err) {
    console.error(`modalSubmit: ${err.message}`);
    alert("Failed to open modal");
    modalClose();
  }
};

const modalClearError = (modal, message = "") => {
  const error = modal.querySelector("#error");
  if (error && (message == "" || error.innerHTML.includes(message))) {
    error.remove();
  }
};

const modalSetError = (modal, message) => {
  message = `<p id="error" style="text-align: center">
        <strong style="color: red">
            ${message}
        </strong>
    </p>`.html();

  const error = modal.querySelector("#error");
  if (error) {
    error.replaceWith(message);
  } else {
    modal
      .querySelector(".modal__body")
      .insertAdjacentElement("afterbegin", message);
  }
};

const modalHandleResult = (element, existing) => {
  if (element === undefined) {
    existing?.remove();
    document.dispatchEvent(new Event("delete-item"));
  } else {
    if (existing) {
      existing.replaceWith(element);
    } else {
      items.insertAdjacentElement("afterbegin", element);
      document.dispatchEvent(new Event("append-item"));
    }
  }
};

const modalSubmit = async (shouldDelete = false, modalId = "modal") => {
  const modal = document.getElementById(modalId);
  if (modal)
    try {
      const form = modal.querySelector("form");

      if (!form) {
        throw new Error("Form not found");
      }
      if (!items || (!shouldDelete && !itemTemplate)) {
        throw new Error("Items or templates not found");
      }

      const response = await secureFetch(form.action, {
        method: form.getAttribute("data-method") ?? form.method,
        body: new FormData(form),
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error(response.statusText);
          }
          return response.json();
        })
        .then((response) => {
          return Array.isArray(response) ? response : Array(response);
        });

      response.forEach((item) => {
        const existing = document.getElementById(item.id ?? item);
        modalHandleResult(
          shouldDelete ? undefined : itemTemplate.html(item),
          existing
        );
      });

      modalClose(null, modalId);
    } catch (error) {
      modalSetError(modal, error.message);
    }
};
