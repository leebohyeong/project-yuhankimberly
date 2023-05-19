import {findOne, on} from './helper';

const CLASS_NAME_BODY_OPEN = 'modal-open';
const CLASS_NAME_OPEN = 'modal--open';
const CLASS_NAME_FADE = 'modal--fade';

const Modal = class {
    constructor() {
        this.body = findOne('body');

        this.modals = [];
        this.onEvent();
    }

    open(target) {
        if (!this.modals.length) {
            this.body.classList.add(CLASS_NAME_BODY_OPEN);
        }

        this.modals.push(target);
        target.classList.add(CLASS_NAME_OPEN);
        setTimeout(() => target.classList.add(CLASS_NAME_FADE), 0);

        return this;
    }

    close(targetModal) {
        let index = this.modals.length - 1;

        if (targetModal) {
            index = this.modals.indexOf(targetModal);

            if (index === -1) {
                index = 0;
            }
        }

        const target = this.modals.splice(index, 1)[0];

        if (!target) return;

        target.classList.remove(CLASS_NAME_FADE, CLASS_NAME_OPEN);

        if (!this.modals.length) {
            this.body.classList.remove(CLASS_NAME_BODY_OPEN);
        }
    }

    onClose(event) {
        const close = event.target.closest('.modal__close');

        if (close) {
            if (close.tagName === 'A') event.preventDefault();

            this.close();
        }
    }

    onEvent() {
        on(this.body, 'click', this.onClose.bind(this), false);
    }

    get scrollWidth() {
        return Math.abs(window.innerWidth - document.documentElement.clientWidth);
    }
};

export default Modal;