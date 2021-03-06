{**
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
* @author PrestaShop SA <contact@prestashop.com>
    * @copyright 2007-2019 PrestaShop SA and Contributors
    * @license https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
    * International Registered Trademark & Property of PrestaShop SA
    *}

    {block name='header_banner'}
    {* <div class="header-banner">
        {hook h='displayBanner'}
    </div> *}
    {/block}

    {block name='header_nav'}
    <nav class="header-nav" style="outline:1px solid blue;">
        <div class="container">
            <div class="row">
                <div class="col-md-2 hidden-sm-down" id="_desktop_logo">

                    <a href="{$urls.base_url}">
                        <img class="logo img-responsive" src="{$shop.logo}" alt="{$shop.name}">
                    </a>

                </div>

                <div class="col-md-7 col-sm-12 position-static">
                    {hook h='displayTop'}
                    <div class="clearfix"></div>
                </div>

                <div class="info col-md-3 col-sm-12">

                    <div class="cart searcher__init">
                        <span><img src="{$urls.img_ps_url}search.svg" /></span>
                        <div class="title">szukaj</div>
                    </div>

                    <form method="get" class="searcher display-none" action="{$urls.base_url}pl/search">
                        <input type="hidden" name="controller" value="search">
                        <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
                        <input type="text" name="s" value="" placeholder="Wyszukaj..." aria-label="Search" class="ui-autocomplete-input searcher__input-text" autocomplete="off">
                        <button class="searcher__button disabled-content" type="submit" disabled>
                            <i class="material-icons search"></i>
                            {* <span class="hidden-xl-down">Search</span> *}
                        </button>
                    </form>

                    {hook h='displayNav2'}
                    <div class="social">
                        <div class="cart">
                            <img class="social-icon" src="{$urls.img_ps_url}fb.svg" />
                        </div>
                        <div class="cart">
                            <img class="social-icon" src="{$urls.img_ps_url}ig.svg" />
                        </div>
                    </div>
                </div>
                <div class="hidden-md-up text-sm-center mobile">
                    <div class="float-xs-left" id="menu-icon">
                        <i class="material-icons d-inline">&#xE5D2;</i>
                    </div>
                    <div class="float-xs-right" id="_mobile_cart"></div>
                    <div class="float-xs-right" id="_mobile_user_info"></div>
                    <div class="top-logo" id="_mobile_logo"></div>
                    <div class="clearfix"></div>
                </div>

            </div>
        </div>

    </nav>
    {/block}

    {block name='header_top'}
    <div class="header-top" style="outline:1px solid red;">
        <div class="container">
            <div class="row">
                <div id="mobile_top_menu_wrapper" class="row hidden-md-up" style="display:none;">
                    <div class="js-top-menu mobile" id="_mobile_top_menu"></div>
                    <div class="js-top-menu-bottom">
                        <div id="_mobile_currency_selector"></div>
                        <div id="_mobile_language_selector"></div>
                        <div id="_mobile_contact_link"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {/block}


    {* <nav class="header-nav">
        <div class="container">
            <div class="row">
                <div class="hidden-sm-down">
                    <div class="col-md-5 col-xs-12">
                        {hook h='displayNav1'}
                    </div>
                    <div class="col-md-7 right-nav">
                        {hook h='displayNav2'}
                    </div>
                </div>
                <div class="hidden-md-up text-sm-center mobile">
                    <div class="float-xs-left" id="menu-icon">
                        <i class="material-icons d-inline">&#xE5D2;</i>
                    </div>
                    <div class="float-xs-right" id="_mobile_cart"></div>
                    <div class="float-xs-right" id="_mobile_user_info"></div>
                    <div class="top-logo" id="_mobile_logo"></div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </nav>
    {/block}

    {block name='header_top'}
    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="col-md-2 hidden-sm-down" id="_desktop_logo">
                    {if $page.page_name == 'index'}
                    <h1>
                        <a href="{$urls.base_url}">
                            <img class="logo img-responsive" src="{$shop.logo}" alt="{$shop.name}">
                        </a>
                    </h1>
                    {else}
                    <a href="{$urls.base_url}">
                        <img class="logo img-responsive" src="{$shop.logo}" alt="{$shop.name}">
                    </a>
                    {/if}
                </div>
                <div class="col-md-a10 col-sm-12 position-static">
                    {hook h='displayTop'}
                    <div class="clearfix"></div>
                </div>
            </div>
            <div id="mobile_top_menu_wrapper" class="row hidden-md-up" style="display:none;">
                <div class="js-top-menu mobile" id="_mobile_top_menu"></div>
                <div class="js-top-menu-bottom">
                    <div id="_mobile_currency_selector"></div>
                    <div id="_mobile_language_selector"></div>
                    <div id="_mobile_contact_link"></div>
                </div>
            </div>
        </div>
    </div>
    {hook h='displayNavFullWidth'} *}
    {* {/block} *}