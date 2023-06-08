function setFont(event) {
  const dropdown = event.target;
  dropdown.style.color = "#000000";
  dropdown.removeEventListener("click", setFont);
}

const dropdowns = document.querySelectorAll(".dropdown");
for (let i = 0; i < dropdowns.length; i++) {
  const dropdown = dropdowns[i];
  dropdown.addEventListener("click", setFont);
}
