    <div id="footer_contact_form">
        <form method="POST" class="form" name="footer-contact-form" action="post" enctype="multipart/form-data" novalidate="novalidate">
            <div class="display-none form__field-success field-succes-footer">Twoja wiadomość została!</div>
            <div class="form__row">
                <div class="display-none form__field-error field-error-footer field-error-footer--name"></div>
                <label for="footer_contact_name">
                    <input type="text" name="footer_contact_name" id="footer_contact_name" placeholder="Imię i nazwisko *" required>
                </label>
            </div>
            <div class="form__row">
                <div class="display-none form__field-error field-error-footer field-error-footer--email"></div>
                <label for="footer_contact_email">
                    <input type="email" name="footer_contact_email" id="footer_contact_email" placeholder="Adres email *" required>
                </label>
            </div>
            <div class="form__row">
                <label for="footer_contact_topic">
                    <input type="text" name="footer_contact_topic" id="footer_contact_topic" placeholder="Temat">
                </label>


                <div class="form__row">
                    <div class="display-none form__field-error field-error-footer field-error-footer--message"></div>
                    {* <label for="wiadomosc" class="textarea"> *}
                        <textarea name="footer_contact_message" required placeholder="Treść wiadomości *"></textarea>
                        {* </label> *}
                </div>
                <div class="form__row form__checkbox">
                    <div class="display-none form__field-error field-error-footer field-error-footer--privacy-policy"></div>

                    {* <label for="privacy_policy">
                        <input type="checkbox" name="footer_contact_privacy-policy" id="privacy_policy" required>
                        <span>Potwierdzam zapoznanie się z informacją o przetwarzaniu danych. Poniżej Polityka Prywatności.<br>* Pola wymagane</span>
                    </label> *}

                    <label class="checkbox">
                        <input type="checkbox" name="footer_contact_privacy-policy" id="privacy_policy" required>
                        <span class="checkmark"></span>
                        <span>Potwierdzam zapoznanie się z informacją o przetwarzaniu danych. Poniżej Polityka Prywatności.<br>* Pola wymagane</span>
                    </label>

                </div>
                {* <div class="form__row">
                    <div class="form__row__col">
                        <label {* <div class="form__row">
                            <div class="form__row__col">
                                <label for="numer_zamowienia" class="text_input">
                                    <input type="text" name="numer_zamowienia" id="numer_zamowienia" placeholder="Numer Zamówienia">
                                </label>
                            </div>
                            <div class="form__row__col">
                                <label for="fileUpload" class="file_input" data-content="Załącz plik">
                                    <input type="file" name="fileUpload" id="fileUpload" placeholder="Załącz plik">
                                </label>
                            </div>
                    </div> *}
                    <div class="button__wrap">
                        <button class="button button--send" name="footer_contact_submit">
                            <span>Wyślij</span>
                        </button>
                    </div>
                    {* </button>
                    <input class="button" type="submit" name="footer_contact_submit" value="Wyślij" />
                </div> *}

        </form>
    </div>
    </div>




    {* <div class="form__row">
        <div class="form__row__col">
            <label for="numer_zamowienia" class="text_input">
                <input type="text" name="numer_zamowienia" id="numer_zamowienia" placeholder="Numer Zamówienia">
            </label>
        </div>
        <div class="form__row__col">
            <label for="fileUpload" class="file_input" data-content="Załącz plik">
                <input type="file" name="fileUpload" id="fileUpload" placeholder="Załącz plik">
            </label>
        </div>
    </div> *}