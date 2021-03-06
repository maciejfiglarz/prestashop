/**
 * 2007-2019 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
import "expose-loader?Tether!tether";
import "bootstrap/dist/js/bootstrap.min";
import "flexibility";
import "bootstrap-touchspin";

import "./responsive";
import "./checkout";
import "./customer";
import "./listing";
import "./product";
import "./cart";

import DropDown from "./components/drop-down";
import Form from "./components/form";
import ProductMinitature from "./components/product-miniature";
import ProductSelect from "./components/product-select";
import TopMenu from "./components/top-menu";

import prestashop from "prestashop";
import EventEmitter from "events";

import "./lib/bootstrap-filestyle.min";
import "./lib/jquery.scrollbox.min";

import "./components/block-cart";
import Searcher from "./components/searcher";

import Swiper, { Navigation, Pagination } from "swiper";

import "./lib/owl-carousel/owl.carousel.min.js";
import "./lib/owl-carousel/assets/owl.carousel.min.css";
import "./lib/owl-carousel/assets/owl.theme.default.min.css";

import "./lib/fancybox/jquery.fancybox.min.js";
import "./lib/fancybox/jquery.fancybox.min.css";

// "inherit" EventEmitter
for (var i in EventEmitter.prototype) {
  prestashop[i] = EventEmitter.prototype[i];
}

$(document).ready(() => {
  let dropDownEl = $(".js-dropdown");
  const form = new Form();
  let topMenuEl = $('.js-top-menu ul[data-depth="0"]');
  let dropDown = new DropDown(dropDownEl);
  let topMenu = new TopMenu(topMenuEl);
  let productMinitature = new ProductMinitature();
  let productSelect = new ProductSelect();
  dropDown.init();
  form.init();
  topMenu.init();
  productMinitature.init();
  productSelect.init();

  new Searcher().init();

  $(".owl-carousel").owlCarousel({
    loop: true,
    margin: 29,
    responsiveClass: true,
    autoplay: true,
    autoplayTimeout: 3000,
    autoplayHoverPause: true,
    nav: true,
    stagePadding: 18,
    navText: [
      "<span class='material-icons'>navigate_before</span>",
      "<span class='material-icons'>navigate_next</span>",
    ],
    responsive: {
      0: {
        items: 1,
        nav: false,
        loop: true,
      },
      600: {
        items: 2,
        nav: false,
        loop: true,
      },
      1000: {
        items: 4,
        nav: false,
        loop: true,
      },
    },
  });
  $('[data-fancybox="gallery"]').fancybox({
    // Options will go here
  });

});
