/*
 * Custom code goes here.
 * A template should always ship with an empty custom.js
 */

const select = document.querySelector('select[name="submitVoivodeship"]');

if (select) {
  select.addEventListener("change", function (e) {
    const value = e.currentTarget.options[e.currentTarget.selectedIndex].value;
    const brands = document.querySelectorAll(".brand");
    brands.forEach((brand) => {
      console.log("brand", brand.dataset.type,value);
      if (value == "all") {
        brand.style.display = "block";
      } else {
        if ((brand.dataset.type == value)) {
          brand.style.display = "block";
        } else {
          brand.style.display = "none";
        }
      }
    });
  });
}
