let arrow = document.querySelectorAll(".arrow");
console.log(arrow);
for (var i = 0; i < arrow.length; i++) {
  arrow[i].addEventListener("click", (e) => {
    let arrowParent = e.target.parentElement.parentElement;
    console.log(arrowParent);
    arrowParent.classList.toggle("showMenu");
  });
}
let sideBarBtn = document.querySelector(".bx-menu");
let sideBarBtnMini = document.querySelector(".sidebar-mini .bx-menu");
console.log("sidebar button: ", sideBarBtnMini);

let sideBar = document.querySelector(".sidebar");
let sideBarMini = document.querySelector(".sidebar-mini");
console.log("sidebar: ", sideBarMini);

sideBarBtn.parentElement.addEventListener("click", () => {
  console.log("clicked");

  sideBarMini.classList.remove("close");
  sideBarMini.classList.add("open");
  sideBar.classList.remove("open");
  sideBar.classList.toggle("close");
});

sideBarBtnMini.parentElement.addEventListener("click", () => {
  console.log("clicked");
  sideBarMini.classList.toggle("open");
  sideBarMini.classList.toggle("close");
  sideBar.classList.toggle("close");
  sideBar.classList.toggle("open");
});

// window.addEventListener("resize", () => {
//   if (window.innerWidth < 780) {
//     if (sideBar.classList.contains("open")) {
//       sideBar.classList.remove("open");
//       sideBar.classList.add("close");
//     }

//     if (sideBarMini.classList.contains("close")) {
//       sideBarMini.classList.remove("close");
//       sideBarMini.classList.add("open");
//     }
//   }
// });

// window.addEventListener("resize", () => {
//   if (window.innerWidth > 780) {
//     if (sideBar.classList.contains("close")) {
//       sideBar.classList.remove("close");
//       sideBar.classList.add("open");
//     }

//     if (sideBarMini.classList.contains("open")) {
//       sideBarMini.classList.remove("open");
//       sideBarMini.classList.add("close");
//     }
//   }
// });

let wasLessThan780 = window.innerWidth < 780;

window.addEventListener("resize", () => {
  if (window.innerWidth < 780 && !wasLessThan780) {
    if (sideBar.classList.contains("open")) {
      sideBar.classList.remove("open");
      sideBar.classList.add("close");
    }

    if (sideBarMini.classList.contains("close")) {
      sideBarMini.classList.remove("close");
      sideBarMini.classList.add("open");
    }

    wasLessThan780 = true;
  } else if (window.innerWidth > 780 && wasLessThan780) {
    if (sideBar.classList.contains("close")) {
      sideBar.classList.remove("close");
      sideBar.classList.add("open");
    }

    if (sideBarMini.classList.contains("open")) {
      sideBarMini.classList.remove("open");
      sideBarMini.classList.add("close");
    }

    wasLessThan780 = false;
  }
});
