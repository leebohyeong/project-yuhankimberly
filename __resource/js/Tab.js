import {findOne, find, on} from './helper';

const Tab = class {
    constructor(element, callback) {
        this.element = element;
        this.menus = find('.tab__menu', this.element);
        this.panels = this.menus.map(menu => {
            const panelId = menu.getAttribute('aria-controls');
            const panel = findOne(`#${panelId}`);

            return panel;
        });

        this.current = 0;
        this.panelTimer = null;

        this.callback = callback;

        this.initEvents();
    }

    toggleTab() {
        this.menus.forEach((menu, index) => {
            if (this.current === index) {
                menu.classList.add('tab__menu--active');
                menu.setAttribute('aria-selected', true);
            } else {
                menu.classList.remove('tab__menu--active');
                menu.setAttribute('aria-selected', false);
            }
        });
    }

    togglePanel() {
        this.panels.forEach((panel, index) => {
            if (this.current === index) {
                panel.classList.add('tab__panel--in');
                this.panelTimer && clearTimeout(this.panelTimer);
                this.panelTimer = setTimeout(() => panel.classList.add('tab__panel--active'), 13);
            } else {
                panel.classList.remove('tab__panel--in', 'tab__panel--active');
            }
        });
    }

    toggle() {
        this.toggleTab();
        this.togglePanel();
    }

    initEvents() {
        this.menus.forEach((menu, index) => {
            on(menu, 'click', (event) => {
                event.preventDefault();

                this.current = index;
                this.toggle();

                this.callback && this.callback();
            });
        });
    }
};

export default Tab;