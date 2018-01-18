/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_Salesforce extension
 * NOTICE OF LICENSE
 *
 * @category Magenest
 * @package  Magenest_Salesforce
 * @author   ThaoPV
 */
define(
    [
    "jquery",
    "Magento_Ui/js/lib/core/class"
    ],
    function ($, Class) {
        "use strict";
        return Class.extend(
            {
                defaults: {
                    /**
                     * Initialized solutions
                     */
                    url : '',
                    form_key : '',
                    config: {'lead' : 'Leads'},
                    /**
                     * The elements of created solutions
                     */
                    solutionsElements: {},
                    /**
                     * The selector element responsible for configuration of payment method (CSS class)
                     */
                    buttonRefresh: '.button.action-refresh'
                },
                /**
                 * Constructor
                 */
                initialize: function (url) {
                    this.initConfig(url)
                    .initMappings(url);
                    return this;
                },
                /**
                 * Initialization and configuration solutions
                 */
                initMappings: function (url) {
                    $("select[data-ui-id='map-edit-tab-main-fieldset-element-select-type']").change(
                        function () {
                            var type = $(this).val();
                            var data = {'type' : type};
                            $.ajax(
                                {
                                    type: "POST",
                                    url: url.url,
                                    data: data,
                                    showLoader: true,
                                    success: function (response) {
                                        var responseObj = JSON.parse(response);
                                        console.log(response);
                                        $('select[data-ui-id="map-edit-tab-main-fieldset-element-select-magento"]').html(responseObj.magento_options);
                                        $('select[data-ui-id="map-edit-tab-main-fieldset-element-select-salesforce"]').html(responseObj.salesforce_options);
                                    }
                                }
                            );
                        }
                    );
                    return this;
                }
            }
        );
    }
);
