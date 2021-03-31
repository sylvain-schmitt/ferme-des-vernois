const burger = document.querySelector('.burger');
const navigation = document.querySelector(".nav-links");
const navigationItems = document.querySelectorAll(".nav-links ul li a")
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