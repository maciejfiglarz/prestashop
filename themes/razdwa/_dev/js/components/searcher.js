import prestashop from "prestashop";
import $ from "jquery";

export default class Searcher {
  constructor() {}
  init() {
    const init = $(".searcher__init");
    const searcher = $(".searcher");

    init.on("click", function () {
      searcher.removeClass("display-none");
      init.addClass("opacity-hidden");
      const inputText = $(".searcher__input-text");
      const buttonSubmit = $(".searcher__button");
      inputText.on("input", function (e) {
        if (inputText.val().length > 0) {
          buttonSubmit.removeClass("disabled-content");
          buttonSubmit.prop("disabled", false);
        } else {
          buttonSubmit.addClass("disabled-content");
          buttonSubmit.prop("disabled", true);
        }
      });
    });
  }
}
