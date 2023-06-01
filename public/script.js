 //add active class in selected list item 
 let listitem= document.querySelectorAll('.nav-list-item');
 for(let i=0; i<listitem.length;i++){
     listitem[i].onclick= function(){
         let j=0;
         while(j< listitem.length){
             listitem[j++].className='nav-list-item';
         }
         listitem[i].className='nav-list-item active';
     }
 }

 
//clos sidebar
let menuToggle = document.querySelector('.toggle');
let navigation = document.querySelector('.side-menu');
let main = document.querySelector('.main');
let annonce = document.querySelector('.annonce');
let espace = document.querySelector('.espace');
let info = document.querySelector('.ann');
menuToggle.onclick = function(){
menuToggle.classList.toggle('active');
navigation.classList.toggle('active');
main.classList.toggle('active');
annonce.classList.toggle('active');
espace.classList.toggle('active');
info.classList.toggle('active');}




//dark mode 

const body =document.querySelector("body"),
      sidemenu =body.querySelector(".side-menu"),
      content=body.querySelector(".main"),
      toggle =body.querySelector(".toggle"),
      modeSwitch =body.querySelector(".toggle-switch");

      // check if dark mode is enabled in localStorage
const darkModeEnabled = localStorage.getItem('darkMode') === 'enabled';
if (darkModeEnabled) {
  body.classList.add('dark');
}

modeSwitch.addEventListener("click", () => {
  body.classList.toggle("dark");

  // save user's preference for the dark mode to localStorage
 if (body.classList.contains('dark')) {
    localStorage.setItem('darkMode', 'enabled');
  } else {
    localStorage.setItem('darkMode', 'disabled');
  }
})
        
        
        

//darkmode btn
let btn=document.getElementById("btn");
let btnText=document.getElementById("btnText");
let btnIcon=document.getElementById("btnIcon");

/*btn.onclick= function(){
  document.body.classList.toggle("dark");
  if(document.body.classList.contains("dark")) {
    btnIcon.name = "sunny-outline";
    btnText.innerHTML = "mode lumiére";
    // save user's preference for the dark mode to localStorage
    localStorage.setItem('darkMode', 'enabled');
  } else {
    btnIcon.name = "moon";
    btnText.innerHTML = "mode sombre";
    // save user's preference for the light mode to localStorage
    localStorage.setItem('darkMode', 'disabled');
  }
}*/
let darkModeEnable = localStorage.getItem('darkMode') === 'enabled';
btn.onclick = function() {
if (darkModeEnabled) {
  document.body.classList.toggle('dark');
  btnIcon.name = "sunny-outline";
  btnText.innerHTML = "mode lumiére";
} else {
  document.body.classList.remove('dark');
  btnIcon.name = "moon";
  btnText.innerHTML = "mode sombre";
}
if (document.body.classList.contains("dark")) {
  btnIcon.name = "sunny-outline";
  btnText.innerHTML = "mode lumiére";
  localStorage.setItem('darkMode', 'enabled');
} else {
  btnIcon.name = "moon";
  btnText.innerHTML = "mode sombre";
  localStorage.setItem('darkMode', 'disabled');
}
}

/*btn.onclick = function() {
  document.body.classList.toggle("dark");
  if (document.body.classList.contains("dark")) {
    btnIcon.name = "sunny-outline";
    btnText.innerHTML = "mode lumiére";
    localStorage.setItem('darkMode', 'enabled');
  } else {
    btnIcon.name = "moon";
    btnText.innerHTML = "mode sombre";
    localStorage.setItem('darkMode', 'disabled');
  }
}*/


/*track
const circles = document.querySelectorAll(".circle"),
  progressBar = document.querySelector(".indicator"),
  buttons = document.querySelectorAll("button");
let currentStep = 1;
// function that updates the current step and updates the DOM
const updateSteps = (e) => {
  // update current step based on the button clicked
  currentStep = e.target.id === "next" ? ++currentStep : --currentStep;
  // loop through all circles and add/remove "active" class based on their index and current step
  circles.forEach((circle, index) => {
    circle.classList[`${index < currentStep ? "add" : "remove"}`]("active");
  });
  // update progress bar width based on current step
  progressBar.style.width = `${((currentStep - 1) / (circles.length - 1)) * 100}%`;
  // check if current step is last step or first step and disable corresponding buttons
  if (currentStep === circles.length) {
    buttons[1].disabled = true;
  } else if (currentStep === 1) {
    buttons[0].disabled = true;
  } else {
    buttons.forEach((button) => (button.disabled = false));
  }
};
// add click event listeners to all buttons
buttons.forEach((button) => {
  button.addEventListener("click", updateSteps);
});*/
