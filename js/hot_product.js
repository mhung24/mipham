document.addEventListener("DOMContentLoaded", function () {
  const container = document.querySelector(".product-list-row");
  const btnLeft = document.querySelector(".left-arrow");
  const btnRight = document.querySelector(".right-arrow");

  if (container && btnLeft && btnRight) {
    btnRight.addEventListener("click", function () {
      container.scrollBy({
        left: container.offsetWidth / 2,
        behavior: "smooth",
      });
    });

    btnLeft.addEventListener("click", function () {
      container.scrollBy({
        left: -(container.offsetWidth / 2),
        behavior: "smooth",
      });
    });
  }
});
