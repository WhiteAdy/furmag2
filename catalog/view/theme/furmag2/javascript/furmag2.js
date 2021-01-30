const startScrollTopInterval = () => {
	window.scrollTopInterval = setInterval(() => {
		if (bodyElement.scrollTop === 0) header.classList.remove('sticky');
	}, 300);
};

const headerStickyHandler = (e) => {
	if (e[0].isIntersecting) {
		header.classList.add('sticky');
		clearInterval(window.scrollTopInterval);
	} else {
		startScrollTopInterval();
	}
};

const header = document.getElementsByTagName('header')[0];
const content = document.getElementsByTagName('header')[0].nextElementSibling;
const bodyElement = document.getElementsByTagName('body')[0];

const observer = new IntersectionObserver(headerStickyHandler, {
	rootMargin: '0px 0px -100% 0px',
});
observer.observe(content);

const backdrop = document.getElementById('backdrop');
const searchInput = document.getElementById('searchInput');
const searchBarBorder = document.getElementsByClassName(
	'search-bar-gradient-border'
)[0];
const searchDiv = document.getElementById('search');
const cartDiv = document.getElementById('cart');
const cartButton = document.getElementById('cart-btn');
const toggleBackdrop = () => {
	backdrop.classList.toggle('visible');
};
const cartModal = document.getElementById('cart-modal');
const modalContinueShoppingBtn = document.getElementsByClassName(
	'continue-shopping'
)[0];
let backdropToggleSource = null;
const footerMenuHeaders = document.querySelectorAll('#footer-menu-mobile h5');

const searchClickHandler = () => {
	searchDiv.classList.toggle('search-bar-focused');
	backdropToggleSource = searchInput;
};

const miniCartToggleHandler = () => {
	cartModal.classList.toggle('visible');
	backdropToggleSource = cartButton;
};

const backdropClickHandler = () => {
	toggleBackdrop();
	if (backdropToggleSource === searchInput) searchClickHandler();
	if (backdropToggleSource === cartButton) miniCartToggleHandler();
	backdropToggleSource = null;
};

header.addEventListener('click', (e) => {
	const el = e.target;
	if (el.id === 'cart-modal-close' || el === modalContinueShoppingBtn) {
		backdropClickHandler();
	} else {
		if (el === searchInput) {
			toggleBackdrop();
			searchClickHandler();
		}
		if (el === cartButton) {
			toggleBackdrop();
			miniCartToggleHandler();
		}
		if (el.classList.contains('remove-item-btn')) {
			el.parentElement.classList.add('removed');
			setTimeout(() => {
				cart.remove(el.parentElement.dataset.cart_id);
			}, 250);
		}
		if (el.classList.contains('add')) {
			cart.add(el.parentElement.parentElement.dataset.product_id);
		}
		if (el.classList.contains('subtract')) {
			if (el.nextElementSibling.value > 1) {
				el.nextElementSibling.value--;
				cart.update(
					el.parentElement.parentElement.dataset.cart_id,
					el.nextElementSibling.value
				);
			}
		}
	}
});

backdrop.addEventListener('click', () => {
	backdropClickHandler();
});

// Mobile footer

[...footerMenuHeaders].forEach((item) => {
	item.addEventListener('click', (e) => {
		let list = e.target.nextElementSibling;

		list.style.height === '0px'
			? (list.style.height = list.scrollHeight + 'px')
			: (list.style.height = '0px');
	});
});
