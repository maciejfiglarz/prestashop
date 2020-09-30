<div class="row">
    <div id="footer_contact_form">
        <form method="POST" class="form" action="#footer_contact_form" enctype="multipart/form-data">
            <div class="form__row">
                <label for="footer_contact_name">
                    <input type="text" name="footer_contact_name" id="footer_contact_name" placeholder="Imię i nazwisko *" required>
                </label>
            </div>
            <div class="form__row">
                <label for="footer_contact_email">
                    <input type="email" name="footer_contact_email" id="footer_contact_email" placeholder="Adres email *" required>
                </label>
            </div>
            <div class="form__row">
                <label for="footer_contact_topic">
                    <input type="text" name="footer_contact_topic" id="footer_contact_topic" placeholder="Temat">
                </label>

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
                <div class="form__row">
                    {* <label for="wiadomosc" class="textarea"> *}
                        <textarea name="footer_contact_message" required placeholder="Treść wiadomości *"></textarea>
                        {* </label> *}
                </div>
                <div class="form__row form__checkbox">
                    <label for="privacy_policy">
                        <input type="checkbox" name="footer_contact_privacy-policy" id="privacy_policy" required>
                        <span>Potwierdzam zapoznanie się z informacją o przetwarzaniu danych. Poniżej Polityka Prywatności.<br>* Pola wymagane</span>
                    </label>
                </div>
                {* <div class="form__row">
                    <div class="form__row__col">
                        <label class="submit">
                            <input type="hidden" name="contactFormToken" value="{$contactFormToken}">
                            <input type="submit" name="SubmitFooterForm" value="Wyślij">
                        </label>
                    </div>
                </div> *}

                <div class="button__wrap">
                    <input class="button" type="submit" name="footer_contact_submit" value="Wyślij">
                </div>
        </form>
    </div>
</div>