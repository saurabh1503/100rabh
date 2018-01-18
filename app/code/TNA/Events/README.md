<img src="./app/design/frontend/TNA/standard/web/images/tna-logo.svg" alt="The National Alliance Logo" title="The National Alliance" style="width:280px;height:90px; float: right; display:inline">
<header style="border-bottom: 1px solid rgb(238, 238, 238);margin-top:-40px">
  <h1 style="display:inline;border-bottom:0">TNA - Events Module</h1>
</header>

<h2> Table of Contents </h2>

[TOC]

# Getting Started
<a href="#content" style="float: right; display:inline">Top</a>

Main [README.md](/README.md)

## Functionality

* <s>Setup/InstallData</s>
    1. <s>Instantiates the product attribute set for events</s>
    2. <s>Instantiates the product attribute groups for the event attribute set</s>
        * <s>Verify ordering of attribute groups is acceptable</s>
    3. <s>Instantiates the product attributes for the event product</s>
    4. <s>Assign product attributes to correct attribute groups</s>
    5. <s>Create Catalog category for the Course Schedule</s>
    6. Instantiates the product attribute groups for the membership attribute set
    7. Create Catalog category for Membership

* <s>Setup/InstallSchema</s>
    1. <s>Instantiates the continuing education table</s>
    2. <s>req</s>
* Continuing Education
    1. <s>Backend view of continuing education table in admin panel</s>
    2. Continuing education is synced **from** the oracle database
* Legacy - NACS Integration
    1. <s>Events should be synced **from** the oracle database</s>
        * <s>Cronjob should be added to a custom crongroup in the admin area, so end users can change sync schedule in the Magento Adminpanel under Stores > Configuration > Advanched > System</s>
        * <s>Events should be synced such that `Event Start date is greater than today OR Event is a university event and Event start date is great than today minus 30 days`</s>
        * <s>Events that are already in Magento that do not meet this criteria should be disabled, not removed, so as to keep a record in the system.</s>
    2. Adds an observer either on the event `checkout_onepage_controller_success_action` or on `sales_order_place_after` (needs more research/test to determine proper event).
        * Observer should insert the required order data, including continuing education request data, into NACS
            1. Current Package and function for insertion: `$@"DECLARE result varchar2(1000); BEGIN  @0 := CIC3.CARTJSON.REQUESTENTRY(@1); END;";`
            2. This should have the ability to properly insert order information for **events** and for **membership payments**.
        * Error handling
            1. Should log the order, time, and error codes.
            2. Notify the current Magento administrators in the case db insertion fails.
            3. Should retry insertion periodically for the backlog of failed insertion transactions until service returns to normal.
* GateKeeper - Continuing education
    1. Must present continuing education "terms and conditions" during the checkout flow for all events
* Request Continuing education during checkout
    1. The option to request continuing education for an event during registration
    2. Events are pre certified by each state license to offer a certain number of credits for an event.
* Membership Payment
    1. The ability to make membership payments for designations through Magento.
* Membership
    1. Certain products (MEGA Seminars) require a participant to be a membership paying member of a certain program to enroll. If they are not a member, they should be required to pay their membership to be allowed to enroll.
* ERP (Fusion) - Integration
    1. [Oracle Fusion Soap API Docs](http://docs.oracle.com/cloud/farel11/common/OESWA/)
* Course Schedule
    1. Event Filtering
        1. Currently, using the Mageplaza/LayeredNaviationUltimate module for product filtering capabilities
        2. Attributes that will be filtered upon should be of type dropdown with filtering on.
            * Event Location
            * Program
            * Institute
            * Date (Start and End)
            * Learning Options
            * CE License State
            * CE Hours
* Register for Others

## QA Requirements



```
```


```
```

# Running the tests
<a href="#content" style="float: right; display:inline">Top</a>

Explain how to run the automated tests for this system

```
grunt test
```

## Break down into end to end tests

Explain what these tests test and why

```
```

## And coding style tests



```
```

# Information
<a href="#content" style="float: right; display:inline">Top</a>

## Contributing

Please read [CONTRIBUTING.md](./CONTRIBUTING.md) for details on our code of conduct, and the process for submitting code review.

## License

See [LICENSE.md](./LICENSE.md) file for details.

## Acknowledgments

* Hat tip to anyone who's code was used
* Inspiration
* etc

