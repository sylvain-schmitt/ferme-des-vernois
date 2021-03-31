window.addEventListener("scroll", function(){
    const header = document.querySelector("header");
    header.classList.toggle('sticky', window.scrollY > 200);
});


const burger = document.querySelector('.burger');
const navigation = document.querySelector(".nav-links");
const module = document.querySelector(".nav-module");
const navigationItems = document.querySelectorAll(".nav-links a")
burger.addEventListener('click',()=>{
    //Burger Animation
    burger.classList.toggle('toggle');
    navigation.classList.toggle("active");

    navigationItems.forEach((navigationItem) => {
        navigationItem.addEventListener("click", () => {
            burger.classList.remove("toggle");
            navigation.classList.remove("active");
        });
    });
});