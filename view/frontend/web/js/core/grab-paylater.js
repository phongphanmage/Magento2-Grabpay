/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/* @api */

define([
    'jquery'
], function (jQuery
) {
    GrabWidget = window.GrabWidget || {};
    GrabWidget = function() {
        var page_type = false;
        var page_resource_flag = false;
        var availableInstallment = 4;
        var data_money_format = "${{amount}}";
        var data_money_thousands = ",";
        var data_money_decimal = ".";
        var currency_code = '';
        var data_min_price = 0;
        var data_max_price = 100000000000000;
        var data_currency_code = '';
        var placeholderRegex = /\{\{\s*(\w+)\s*\}\}/;
        var assertBaseURI = 'https://cdn-gp01.grabpay.com/paylater/images/v2/';
        var grabLogo = assertBaseURI + 'grab_logo.svg';
        var grabDarkLogo = assertBaseURI + 'grab_logo_dark.svg';
        var grabModalLogo = assertBaseURI + 'paylater_by_grab_logo.svg';
        var EarnRewardLogo = assertBaseURI + 'earn_rewards.svg';
        var FlexibleOptionsLogo = assertBaseURI + 'flexible_options.svg';
        var NoHiddenFeeLogo = assertBaseURI + 'no_hidden_fee.svg';
        var grabTemplatesAlignment = {
            "left": "l_widget",
            "center": "c_widget",
            "right": "r_widget",
            "right_left": "m_r_d_l_widget",
            "left_right": "m_l_d_r_widget",
            "center_left": "m_c_d_l_widget",
            "center_right": "m_c_d_r_widget",
            "left_center": "m_l_d_c_widget",
            "right_center": "m_r_d_c_widget"
        };
        var grabTemplatesSize = {
            "very_small": "size_vs",
            "small": "size_s",
            "medium": "size_m",
            "large": "size_l",
            "very_large": "size_vl"
        };
        var grabTemplates = {
            "single_line": "<div class='single_line'><div class='row'><span class='left'>or 4 payments of <span class='installment_amount'>{{grab_installment_amount}}</span></span><span class='right'> with&nbsp;<img class='grab_logo' src='{{grab_logo}}'/> <a class='grab_popup_link' href='javascript:void(0);'>Info</a></span></div></div>",
            "single_line_short": "<div class='single_line_short'><div class='row'><span class='left'>or 4 X <span class='installment_amount'>{{grab_installment_amount}}</span></span><span class='right'> with&nbsp;<img class='grab_logo' src='{{grab_logo}}'/> <a class='grab_popup_link' href='javascript:void(0);'>Info</a></span></div></div>",
            "single_line_short_v2": "<div class='single_line_short'><div class='row'><span class='left'>or 4 X <span class='installment_amount'>{{grab_installment_amount}}</span></span><span class='right'> with&nbsp;<img class='grab_logo' src='{{grab_logo}}'/></span></div></div>",
            "multi_line": "<div class='multi_line'><div class='row'><span>or <span class='installment_amount'>{{grab_installment_amount}}</span> X 4 monthly payments.</span></div><div class='row'><span class='left'>Earn rewards*, 0% interest</span><span class='right'> with&nbsp;<img class='grab_logo' src='{{grab_logo}}'/> <a class='grab_popup_link' href='javascript:void(0);'>Info</a></span></div></div>",
        };
        __invoke = function() {
            //if(window.grab_widget_visiblity == '0' ) { return; }
            self.__detectMoneyFormat();
            if (window.grab_widget_min !== undefined) {
                data_min_price = parseFloat(window.grab_widget_min);
            }
            if (window.grab_widget_max !== undefined) {
                data_max_price = parseFloat(window.grab_widget_max);
            }
            self.__addElement();
            if (!self.page_resource_flag) {
                self.__addElementResources();
                self.page_resource_flag = true;
            }
        };

        __extractPrice = function(price_str) {
            var price_list = price_str.match(/\d+(?:\.\d+)?/g);
            var _price = 0;
            if (price_list == null) {
                return _price;
            }
            if (price_list.length == 1) {
                _price = price_list[0];
            }
            return _price;
        };

        __bindObserver = function(e, t) {
            if (null === e) return;
            new MutationObserver(t).observe(e, {
                attributes: true,
                childList: true,
                characterData: true,
                subtree: true
            });
        };
        __registerCustomMutation = function(e, t) {
            function callback(mutation_list, observer) {
                observer.disconnect();
                self.__renderCustomTagline(t);
            }
            if (e != null) {
                __bindObserver(e, callback);
            }
        };
        __nearestElement = function(e, t) {
            if (null === e || e.nodeName == "#document") return e;
            if (e.matches(t)) return e;
            var r = e.querySelector(t);
            return null === r ? self.__nearestElement(e.parentNode, t) : r
        };
        __filterBeforePriceExract = function(el) {
            el.querySelectorAll('.grab-price-divider-widget').forEach(e => e.remove());
            var _text = el.textContent;
            if (_text == null) return '0';
            _text = _text.replaceAll(data_money_thousands, "");
            _text = _text.replaceAll(data_money_decimal, ".");
            return _text;
        };
        __addElement = function() {
            var grab_widget_list = document.querySelectorAll(".grab-price-divider-widget");
            console.log(grab_widget_list);
            grab_widget_list.forEach(function(grab_widget_element) {
                self.__renderCustomTagline(grab_widget_element);
            });
        };
        __addElementResources = function() {
            var head = document.head;
            var link = document.createElement("link");
            link.rel = "stylesheet";
            link.href = "https://fonts.googleapis.com/css2?family=Inter:wght@200;400;500;600;700;800&display=swap";
            head.appendChild(link);

            var link = document.createElement("link");
            link.rel = "stylesheet";
            link.href = "https://cdn-gp01.grabpay.com/paylater/css/v2/grab_widget.css";
            head.appendChild(link);

            document.addEventListener("click", function(e) {
                if (e.srcElement.matches(".grab_widget .left, .grab_widget .right, .grab_widget .grab_popup_link , .grab_widget span, .grab_widget .grab_logo")) {
                    e.preventDefault();
                    GrabWidget.openInfoBox(e.srcElement);
                }
            });
        };
        __renderCustomTagline = function(grab_widget_element) {
            var price_selector = null,
                o = null,
                widget_holder = grab_widget_element;
            var widget_type = 'single';
            if (grab_widget_element.hasAttribute("data-price-selector")) {
                var price_selector = grab_widget_element.getAttribute("data-price-selector");
                o = self.__nearestElement(grab_widget_element, price_selector);
            }
            if (grab_widget_element.hasAttribute("data-widget-type")) {
                widget_type = grab_widget_element.getAttribute("data-widget-type");
            }
            if (grab_widget_element.hasAttribute("data-currency-code")) {
                self.data_currency_code = grab_widget_element.getAttribute("data-currency-code");
            }
            if (grab_widget_element.hasAttribute("data-money-format")) {
                data_money_format = grab_widget_element.getAttribute("data-money-format");
            }
            self.__buildCustomInstallmentElement(widget_type, widget_holder, o);
            if (o != null) {
                self.__registerCustomMutation(o, widget_holder);
            } else {
                self.__registerCustomMutation(widget_holder, widget_holder);
            }
        };
        __buildCustomInstallmentElement = function(widget_type, widget_holder, price_selector) {
            var widget_template = grabTemplates['single_line'];
            if (grabTemplates[widget_type]) {
                widget_template = grabTemplates[widget_type];
            }
            if (price_selector != null) {
                var price_selector_text = self.__filterBeforePriceExract(price_selector);
                var __price = self.__extractPrice(price_selector_text);
            } else {
                if (widget_holder.hasAttribute("data-product-price")) {
                    var __price = parseFloat(widget_holder.getAttribute("data-product-price"));
                } else {
                    var __price = null;
                }
            }

            if (widget_holder.hasAttribute("data-min-price")) {
                var custom_data_min_price = parseFloat(widget_holder.getAttribute("data-min-price"));
            } else {
                var custom_data_min_price = 0;
            }

            if (widget_holder.hasAttribute("data-max-price")) {
                var custom_data_max_price = parseFloat(widget_holder.getAttribute("data-max-price"));
            } else {
                var custom_data_max_price = 9999999999999999;
            }

            if (widget_holder.hasAttribute("data-widget-size")) {
                if(grabTemplatesSize[widget_holder.getAttribute("data-widget-size")]){
                    widget_holder.classList.add(grabTemplatesSize[widget_holder.getAttribute("data-widget-size")]);
                }else{
                    widget_holder.classList.add(grabTemplatesSize["medium"]);
                }
            }else{
                widget_holder.classList.add(grabTemplatesSize["medium"]);
            }

            if (widget_holder.hasAttribute("data-widget-alignment")) {
                if(grabTemplatesAlignment[widget_holder.getAttribute("data-widget-alignment")]){
                    widget_holder.classList.add(grabTemplatesAlignment[widget_holder.getAttribute("data-widget-alignment")]);
                }else{
                    widget_holder.classList.add(grabTemplatesAlignment["left"]);
                }
            }else{
                widget_holder.classList.add(grabTemplatesAlignment["left"]);
            }

            if (isNaN(__price) || __price == 0 || __price == null) return null;
            if (__price < data_min_price || __price > data_max_price) return null;
            if (__price < custom_data_min_price || __price > custom_data_max_price) return null;
            var __installment_price = (__price / availableInstallment),
                __installmentText = widget_template.replace("{{grab_logo}}", grabLogo).replace("{{black_grab_logo}}", grabDarkLogo).replace("{{grab_installment_amount}}", self.__formatMoney(__installment_price, (window.grab_widget_money_format || data_money_format)));
            widget_holder.classList.add("grab_" + widget_type + "_widget");
            widget_holder.classList.add("grab_widget");
            widget_holder.setAttribute("installment_price", __installment_price);
            widget_holder.innerHTML = __installmentText;
        };
        __detectMoneyFormat = function() {
            if (window.grab_data_currency_code !== undefined) {
                self.data_currency_code = window.grab_data_currency_code;
            }
            var formatString = (window.grab_widget_money_format || data_money_format);
            switch (formatString.match(placeholderRegex)[1]) {
                case 'amount':
                case 'amount_no_decimals':
                    break;
                case 'amount_with_comma_separator':
                case 'amount_no_decimals_with_comma_separator':
                    data_money_thousands = ".";
                    data_money_decimal = ",";
                    break;
            }
        };
        __formatMoney = function(cents, format) {
            if (typeof cents == 'string') {
                cents = cents.replace('.', '');
            }
            var value = '';
            var formatString = (format || data_money_format);

            function defaultOption(opt, def) {
                return (typeof opt == 'undefined' ? def : opt);
            }

            function formatWithDelimiters(number, precision, thousands, decimal) {
                precision = defaultOption(precision, 2);
                thousands = defaultOption(thousands, ',');
                decimal = defaultOption(decimal, '.');
                if (isNaN(number) || number == null) {
                    return 0;
                }
                number = (number).toFixed(precision);
                var parts = number.split('.'),
                    dollars = parts[0].replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1' + thousands),
                    cents = parts[1] ? (decimal + parts[1]) : '';
                return dollars + cents;
            }
            switch (formatString.match(placeholderRegex)[1]) {
                case 'amount':
                    value = formatWithDelimiters(cents, 2);
                    break;
                case 'amount_no_decimals':
                    value = formatWithDelimiters(cents, 0);
                    break;
                case 'amount_with_comma_separator':
                    value = formatWithDelimiters(cents, 2, '.', ',');
                    break;
                case 'amount_no_decimals_with_comma_separator':
                    value = formatWithDelimiters(cents, 0, '.', ',');
                    break;
            }
            return formatString.replace(placeholderRegex, value);
        };
        __getModalLink = function(e) {
            if (e.hasAttribute("data-currency-code")) {
                var modal_data_currency_code = e.getAttribute("data-currency-code");
            } else {
                var modal_data_currency_code = self.data_currency_code;
            }
            var modal_link = "https://www.grab.com/finance/pay-later";
            switch (modal_data_currency_code.toUpperCase().trim()) {
                case 'SGD':
                    modal_link = "https://www.grab.com/sg/finance/pay-later/";
                    break;

                case 'MYR':
                    modal_link = "https://www.grab.com/my/finance/pay-later/";
                    break;

                case 'PHP':
                    modal_link = "https://www.grab.com/ph/finance/pay-later/";
                    break;

                default:
                    modal_link = "https://www.grab.com/finance/pay-later";
            }
            return modal_link;
        };
        __closeInfoBox = function() {
            document.querySelectorAll(".grab_pdt_div_modal").forEach(function(e) {
                e.remove();
            });
        };
        __openInfoBox = function(e) {
            var element_box = self.__nearestElement(e, '.grab-price-divider-widget');
            if (element_box.hasAttribute("data-money-format")) {
                data_money_format = element_box.getAttribute("data-money-format");
            }
            var installment_price = parseFloat(element_box.getAttribute("installment_price"));
            var grab_pdt_div_modal = document.createElement("div");
            grab_pdt_div_modal.className = 'grab_pdt_div_modal open';
            grab_pdt_div_modal.innerHTML = `<div class="grab_pdt_container"><div class="grab_pdt_action_bar"><a class="grab_pdt_action_close" href="javascript:void(0)">x</a></div><div class="top_row"> <img src="` + grabModalLogo + `" class="logo_img" /><ul class="info_features"><li><span class="img_info"><img src="` + NoHiddenFeeLogo + `" /></span><span class="text_info">No Hidden<br/>Fees</span></li><li><span class="img_info"><img src="` + EarnRewardLogo + `" /></span><span class="text_info">Earn<br/>Rewards*</span></li><li><span class="img_info"><img src="` + FlexibleOptionsLogo + `" /></span><span class="text_info">Flexible<br/>Payments</span></li></ul></div><div class="middle_row"><h3 class="pay_totay_title">What you pay today</h3><ul class="pay_totay_option"><li><span class="pay_money">` + self.__formatMoney(0, (window.grab_widget_money_format || data_money_format)) + `</span><span class="note">If you choose to pay next month</span></li><li><span class="pay_money"> ` + self.__formatMoney(installment_price, (window.grab_widget_money_format || data_money_format)) + `</span><span class="note">If you choose to pay in 4 installments</span></li></ul></div><div class="bottom_row"><p>*GrabRewards are currently issued when you pay in full or pay next month only. Rewards for payments made in instalments are coming soon. </p> <a class="grab_pdt_action_learnmore" href="` + self.__getModalLink(element_box) + `" target="_blank">Get Started</a></div></div>`;
            var grab_pdt_div_modal_ctn = document.querySelector(".grab_pdt_div_modal");
            if (grab_pdt_div_modal_ctn != null) {
                self.__closeInfoBox();
            }
            document.body.appendChild(grab_pdt_div_modal);
            document.querySelector('.grab_pdt_action_close').addEventListener("click", function() {
                self.__closeInfoBox();
            });
        };
        return {
            "invoke": __invoke,
            "openInfoBox": __openInfoBox
        }
    }();

    return GrabWidget;
});