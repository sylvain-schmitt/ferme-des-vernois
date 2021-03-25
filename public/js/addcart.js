document.querySelectorAll('a.js-addcart').forEach(function (link){
    link.addEventListener('click', onClickBtnLike)
})
function onClickBtnLike(event) {
    event.preventDefault();

    const url = this.href;
    const button = this.querySelector('span.js-disable')
    axios.get(url).then(function (response) {
        console.log(button)
        button.style.display = "none";

    })
}