/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*global define*/
define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Ui/js/block-loader'
], function ($, ko, Component, blockLoader) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Magenest_Salesforce/report',
            targetUrl: '',
            loaderFileUrl: ''
        },
        requestLogList: ko.observableArray([]),
        logList: ko.observableArray([]),
        isLoading: ko.observable(false),

        initialize: function () {
            return this._super()._create();
        },

        _create: function () {
            blockLoader(this.loaderFileUrl);
        },
        
        getHistory: function () {
            var self = this;
            var showResult = $('[data-block="show-report-result"]');
            showResult.show();
            self.isLoading(true);
            var startDate = $('[data-role="show-report-date"] :input[type=text]:first').val();
            var endDate = $('[data-role="show-report-date"] :input[type=text]:last').val();
            var serviceUrl = this.targetUrl + '?start_date=' + startDate + '&end_date=' + endDate;
            self.logList([]);
            self.requestLogList([]);
            return $.ajax({
                url: serviceUrl,
                data: {},
                type: 'GET'
            }).done(
                function (response) {
                    var jsonData = JSON.parse(JSON.stringify(response));
                    if (jsonData.items.length > 0) {
                        for (var i = 0; i < jsonData.items.length; i++) {
                            var item = jsonData.items[i];
                            if (typeof(item.date) != 'undefined') {
                                self.requestLogList.push(item);
                            }
                            if (typeof(item.salesforce_table) != 'undefined') {
                                if (typeof(item.count_failed) == 'undefined') {
                                    item.count_failed = 0;
                                }
                                self.logList.push(item);
                            }
                        }
                    }
                    self.isLoading(false);
                }
            );
        }
    });
});
