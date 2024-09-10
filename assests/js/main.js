// Scroll reveal animation
const observer = new IntersectionObserver((entries) => {
	entries.forEach((entry) => {
		console.log(entry)
		if (entry.isIntersecting) {
			entry.target.classList.add('show');
		} else {
			entry.target.classList.remove('show');
		}
	});
});

// left to right scroll reveal
const hiddenElements = document.querySelectorAll('.hidden');
hiddenElements.forEach((el) => observer.observe(el));
// right to left scroll reveal
const hiddenElements1 = document.querySelectorAll('.hidden1');
hiddenElements1.forEach((el1) => observer.observe(el1));

// Search button animation
const searchBox = document.querySelector('.search-box'); 
const searchBtn = document.querySelector('.search-btn');
const navsearchForm = document.querySelector('.nav-search-form');
const closeBtn = document.querySelector('.close-btn');

searchBtn.addEventListener('click', function(){
	if(navsearchForm.classList.contains('active-nav-search')){
		searchBox.value = ''
	} else {
		navsearchForm.classList.add('active-nav-search');
		searchBox.focus();
	}
})

closeBtn.addEventListener('click', function(){
	navsearchForm.classList.remove('active-nav-search');
	searchBox.value = '';
})

// Sidebar animation
const sidebar = document.querySelector('.sidebar');
const toggleBtn = document.querySelector('.toggle_btn');

toggleBtn.addEventListener('click', () => {
	sidebar.classList.toggle('active_sidebar_element');
});