jQuery(document).ready(function ($) {
  $(".portfolio-category-link").click(function (e) {
      e.preventDefault();

      let filterValue = $(this).parent().data("filter");

      $(".portfolio-filter").removeClass("active");
      $(this).parent().addClass("active");

      $(".portfolio-item").each(function () {
        let categoryAttr = $(this).attr("data-categories") || "uncategorized"; // Use correct attribute
        let categories = categoryAttr.split(" "); // No need to check length, `split()` handles it
    
        if (filterValue === "all" || categories.includes(filterValue)) {
            $(this).removeClass("hidden").css({ visibility: "visible", opacity: "1", height: "auto" });
        } else {
            $(this).addClass("hidden").css({ visibility: "hidden", opacity: "0", height: "0" });
        }
      });
  });
});
