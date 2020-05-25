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
const homeContent = document.getElementById('common-home');
const bodyElement = document.getElementsByTagName('body')[0];

const observer = new IntersectionObserver(headerStickyHandler, {
	rootMargin: '0px 0px -100% 0px',
});
observer.observe(homeContent);

const backdrop = document.getElementById('backdrop');
const searchInput = document.getElementById('searchInput');

// searchInput.addEventListener('click', () => {
// 	backdrop.classList.toggle('visible');
// });
