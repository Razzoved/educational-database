const propertyScope = ':scope > .property__children >';

HTMLDivElement.prototype.input = function() {
    return this.querySelector(':scope > .property__input');
}

const propertyLockUnlock = () => {
    const prev = document.getElementById('property-prev');
    const next = document.getElementById('property-next');
    prev.removeAttribute('hidden');
    next.removeAttribute('hidden');
    const current = propertyRoot.querySelector('.property__item--current');
    if (current && current.previousElementSibling ) {
        prev.removeAttribute('disabled');
    }
    if (current && current.nextElementSibling) {
        next.removeAttribute('disabled');
    }
    propertyRoot.classList.toggle('property--unlocked')
};

const propertyToggle = (element) => {
    element.classList.toggle('property__item--active');
    return element.classList.contains('property__item--active');
    // Array.from(element.querySelectorAll(':scope > .property__children > .property__item')).forEach(p => {
    //     propertyToggle(null, p, state);
    // })
}

const propertyShow = (element) => {
    const scope = ':scope > .property__children >';
    element = element.parentElement?.closest('.property__item');
    while (element) {
        if (!element.classList.contains('property__item--active')) {
            element.classList.add('property__item--shown');
        }
        element = element.parentElement?.closest('.property__item');
    }
}

const propertyHide = (element) => {
    element = element.parentElement?.closest('.property__item');
    while (element) {
        const keepActive = element.querySelector(`${propertyScope} .property__item--active, ${propertyScope} .property__item--shown`) !== null;
        if (!keepActive) {
            element.classList.remove('property__item--active');
        }
        element = element.parentElement?.closest('.property__item');
    }
}

const propertyPrev = (btn) => {
    const current = document.getElementById('property0').querySelector('.property__item--current');
    if (!current.previousElementSibling) {
        btn.setAttribute('disabled', '');
    } else {
        btn.removeAttribute('disabled');
        current.classList.remove('property__item--current');
        current.previousElementSibling.classList.add('property__item--current');
    }
}

const propertyNext = (btn) => {
    const current = document.getElementById('property0').querySelector('.property__item--current');
    if (!current.nextElementSibling) {
        btn.setAttribute('disabled', '');
    } else {
        btn.removeAttribute('disabled');
        current.classList.remove('property__item--current');
        current.nextElementSibling.classList.add('property__item--current');
    }
}

const propertyShowUp = (element) => {

}

const propertyHideUp = (element) => {
    while (element !== null) {
        if (element.classList.contains('property__item--active')) return;
        if (element.querySelector(
            `${propertyScope} property__item--shown, ` +
            `${propertyScope} property__item--active`
        )) {
            element.classList.remove('property__item--shown');
        }
        element = element.parentElement?.closest('.property__item');
    }
}

const propertyHideDown = (element) => {
    Array.from(element.querySelectorAll(
        `.property__item--shown, ` +
        `.property__item--active`
    )).forEach(e => {
        e.classList.remove('property__item--shown', 'property__item--active');
        e.input().setAttribute('disabled', '');
    });
}

document.getElementById('property0').addEventListener('click', (event) => {
    event.stopPropagation();

    const target = event.target.closest('.property__item');
    console.log(target);

    const isActive = propertyToggle(target);
    if (isActive) {
        propertyShowUp(target.parentElement);
    } else {
        propertyHideUp(target.parentElement);
        propertyHideDown(target);
    }
});
