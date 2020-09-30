/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2020 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 *
 * Don't forget to prefix your containers with your own identifier
 * to avoid any conflicts with others containers.
 */

// window.addEventListener("DOMContentLoaded", (event) => {

//     const name = document.querySelector('.testname');

//     console.log("name");

// });

$(document).ready(function () {
  init();
});

const init = () => {
  //   const name = document.querySelector("input[name='footer_contact_name']");
  //   const email = document.querySelector("input[name='footer_contact_email']");
  //   const topic = document.querySelector("input[name='footer_contact_topic']");
  //   const message = document.querySelector(
  //     "input[name='footer_contact_message']"
  //   );
  //   const privatePolicy = document.querySelector(
  //     "input[name='footer_contact_privacy-policy']"
  //   );
  const submit = document.querySelector("input[name='footer_contact_submit']");
  sendMail();
  submit.addEventListener(
    "click",
    (e) => {
      e.preventDefault();
      const formData = fetchFormData();
      if (isValidForm(formData)) {
        // send mail
      }
    },
    false
  );
};

const fetchFormData = () => {
  const name = document.querySelector("input[name='footer_contact_name']")
    .value;
  const email = document.querySelector("input[name='footer_contact_email']")
    .value;
  const topic = document.querySelector("input[name='footer_contact_topic']")
    .value;
  const message = document.querySelector(
    "textarea[name='footer_contact_message']"
  ).value;
  const privatePolicy = document.querySelector(
    "input[name='footer_contact_privacy-policy']"
  ).checked;
  return { name, email, topic, message, privatePolicy };
};

const isValidForm = ({ name, email, message, privatePolicy }) => {
  // this.clearAllErrors();
  let errors = {};
  console.log("name length", name.length);
  if (name.length < 1) {
    errors["contactFooterTitle"] = "Musisz podać imię lub nazwisko";
  }
  if (email.length < 1) {
    errors["contactFooterEmail"] = "Musisz podać email";
  }
  if (email.length < 1) {
    errors["contactFootermessage"] = "Musisz wpisać jakąś wiadomość";
  }
  if (privatePolicy.length < 1) {
  }

  if (Object.keys(errors).length == 0) {
    return true;
  }
  return false;
};

const sendMail = () => {
  const url =
    prestashop.urls.base_url +
    "index.php?fc=module&module=contactfooterform&controller=ajax&ajax=true";
  $.ajax({
    url: url,
    data: { foo: "bar", bar: "foo" },
    type: "post",
    contentType: false,
    processData: false,
    cache: false,
    dataType: "json",
    beforeSend: () => {},
    success: (data) => {
      console.log(data);
    },
  });
  // fetch(url, {
  //   method: "post",
  //   data: ['variable']
  // })
  //   .then((response) => response.json())
  //   .then((response) => {
  //    console.log('response',response);
  //   });
};

// http://localhost/prestashopn/?fc=module&module=contactfooterform&controller=ajax&id_lang=1
