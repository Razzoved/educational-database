const propertyRoot = document.getElementById("property0");
const propertyScope = ":scope > .property__children >";

HTMLDivElement.prototype.input = function () {
  return this.querySelector(":scope > .property__input");
};

const propertyReloadControls = () => {
  const prev = document.getElementById("property-prev");
  const next = document.getElementById("property-next");

  const current = propertyRoot.querySelector(".property__item--current");
  if (current && current.previousElementSibling) {
    prev.removeAttribute("disabled");
  } else {
    prev.disabled = true;
  }
  if (current && current.nextElementSibling) {
    next.removeAttribute("disabled");
  } else {
    next.disabled = true;
  }
};

const propertyLockUnlock = () => {
  // refreshDEFAULT_SORT buttons
  func = propertyRoot.classList.contains("property--unlocked")
    ? (e, x) => e.setAttribute(x, "")
    : (e, x) => e.removeAttribute(x);

  const prev = document.getElementById("property-prev");
  const next = document.getElementById("property-next");

  func(prev, "hidden");
  func(next, "hidden");

  propertyRoot.classList.toggle("property--unlocked");
  document.getElementById("property-toggle").innerText =
    propertyRoot.classList.contains("property--unlocked")
      ? "Disable editing"
      : "Edit";
  propertyReloadControls();
};

const propertyToggle = (element) => {
  element.classList.toggle("property__item--active");
  const isOn = element.classList.contains("property__item--active");
  if (isOn) {
    element.input().removeAttribute("disabled");
  } else {
    element.input().setAttribute("disabled", "");
  }
  return isOn;
};

const propertyPrev = () => {
  let current = propertyRoot.querySelector(".property__item--current");

  document.getElementById("property-next")?.removeAttribute("disabled");

  if (current.previousElementSibling) {
    current.classList.remove("property__item--current");
    current = current.previousElementSibling;
    current.classList.add("property__item--current");
    propertyReloadControls();
  }
};

const propertyNext = () => {
  let current = propertyRoot.querySelector(".property__item--current");
  document.getElementById("property-prev")?.removeAttribute("disabled");

  if (current.nextElementSibling) {
    current.classList.remove("property__item--current");
    current = current.nextElementSibling;
    current.classList.add("property__item--current");
    propertyReloadControls();
  }
};

const propertyShowUp = (element) => {
  while ((element = element.closest(`.property__item:not(#${element.id})`))) {
    element.classList.add("property__item--shown");
  }
};

const propertyHideUp = (element) => {
  while ((element = element.closest(`.property__item:not(#${element.id})`))) {
    if (element.classList.contains("property__item--active")) return;
    if (
      !element.querySelector(
        `${propertyScope} .property__item--shown, ` +
          `${propertyScope} .property__item--active`
      )
    ) {
      element.classList.remove("property__item--shown");
    }
  }
};

const propertySetDown = (element, callback) => {
  Array.from(element.querySelectorAll(`.property__item--active`)).forEach((e) =>
    callback(e)
  );
};

propertyRoot.addEventListener("click", (event) => {
  event.stopPropagation();

  // check for valid target or user click when locked
  if (
    event.target.classList.contains("property__children") ||
    (event.isTrusted && !propertyRoot.classList.contains("property--unlocked"))
  ) {
    return;
  }

  const target = event.target.closest(".property__item");
  if (
    target.parentElement === propertyRoot &&
    !target.querySelector(":scope .property__item")
  ) {
    console.debug("cannot select category", target.id);
    return;
  }

  const isActive = propertyToggle(target);
  if (isActive) {
    propertyShowUp(target);
    propertySetDown(target, (e) => e.input().setAttribute("disabled", ""));
  } else {
    propertyHideUp(target);
    propertySetDown(target, (e) => e.input().removeAttribute("disabled"));
  }
});

document.querySelector("form").addEventListener("submit", (event) => {
  // select all properties from top level categories (categories are not to be added)
  Array.from(
    propertyRoot.querySelectorAll(":scope > .property__item--active")
  ).forEach((category) => {
    const children = Array.from(
      category.querySelectorAll(`${propertyScope} .property__item`)
    );
    category.input().setAttribute("disabled", "");
    children.forEach((tag) => tag.input().removeAttribute("disabled"));
  });
});
