export const findOne = (selector, context = document) => context.querySelector(selector);
export const find = (selector, context = document) => [...context.querySelectorAll(selector)];

export const on = (target, type, callback, capture = false) => {
    if (!target || !target.addEventListener) return;

    target.addEventListener(type, callback, capture);
};
export const off = (target, type, callback) => target.removeEventListener(type, callback);
export const delegate = (target, selector, type, handler, capture) => {
    const dispatchEvent = (event) => {
        const targetElement = event.target;
        const potentialElements = targetElement.closest(selector);

        if (potentialElements) {
            handler.call(potentialElements, event);
        }
    };

    on(target, type, dispatchEvent, !!capture);
};

export const getOffset = (element) => {
    const rect = element.getBoundingClientRect();

    return {
        top: rect.top + window.scrollY,
        left: rect.left + window.scrollX
    };
};

export const debounce = (func, wait = 300) => {
    let inDebounce;

    return (...args) => {
        inDebounce && clearTimeout(inDebounce);
        inDebounce = setTimeout(() => func(...args), wait);
    };
};