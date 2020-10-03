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
 *  @author    Maciej Figlarz <maciejfiglarz333@gmail.com>
 *  @copyright 2007-2020 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 *
 * Don't forget to prefix your containers with your own identifier
 * to avoid any conflicts with others containers.
 */

$(document).ready(function () {
  init();
});

const init = () => {

  const submit = document.querySelector("button[name='footer_contact_submit']");

  submit.addEventListener("click", (e) => {
    e.preventDefault();
    const formData = fetchFormData();
    const success = document.querySelector(".field-succes-footer");
    success.classList.add("display-none");

    if (isValidForm(formData)) {
      success.classList.remove("display-none");
      sendMail(formData);
    }
  });
};

const fetchFormData = () => {
  const form = document.querySelector("form[name='footer-contact-form']");

  const name = form.querySelector("input[name='footer_contact_name']").value;
  const email = form.querySelector("input[name='footer_contact_email']").value;
  const topic = form.querySelector("input[name='footer_contact_topic']").value;
  const message = form.querySelector("textarea[name='footer_contact_message']")
    .value;
  const privatePolicy = form.querySelector(
    "input[name='footer_contact_privacy-policy']"
  ).checked;
  return { name, email, topic, message, privatePolicy };
};

const isValidForm = ({ name, email, message, privatePolicy }) => {
  clearAllErrors();
  let errors = {};
  console.log("name length", name.length);
  if (name.length < 1) {
    errors["name"] = "Musisz podać imię lub nazwisko";
  }
  if (email.length < 1) {
    errors["email"] = "Musisz podać email";
  }
  if (message.length < 1) {
    errors["message"] = "Musisz wpisać jakąś wiadomość";
  }
  if (!privatePolicy) {
    errors["privacy-policy"] = "Musisz wpisać treść wiadomość";
  }

  if (Object.keys(errors).length == 0) {
    return true;
  }
  showErrors(errors);
  return false;
};

const showErrors = (errors) => {
  console.log("errors", errors);
  const form = document.querySelector("form[name='footer-contact-form']");
  for (var key of Object.keys(errors)) {
    const errorField = form.querySelector(`.field-error-footer--${key}`);
    errorField.classList.remove("display-none");
    errorField.innerText = errors[key];
  }
};

const clearAllErrors = () => {
  const errors = document.querySelectorAll(".field-error-footer");
  errors.forEach((error) => {
    error.classList.add("display-none");
    error.innerText = "";
  });
};

const sendMail = ({ name, email, message }) => {
  const url =
    prestashop.urls.base_url +
    "index.php?fc=module&module=contactfooterform&controller=ajax&ajax=1&action=sync";
  $.ajax({
    url: url,
    data: {
      ajax: true,
      name,
      email,
      message,
    },
    type: "post",
    // contentType: false,
    // processData: false,
    // cache: false,
    dataType: "json",
    beforeSend: () => {},
    success: (data) => {
      console.log(data);
    },
  });
};


