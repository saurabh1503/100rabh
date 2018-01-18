<img src="./app/design/frontend/TNA/standard/web/images/tna-logo.svg" alt="The National Alliance Logo" title="The National Alliance" style="width:280px;height:90px; float: right; display:inline">
<header style="border-bottom: 1px solid rgb(238, 238, 238);margin-top:-40px">
  <h1 style="display:inline;border-bottom:0">TNA - Bookstore Module</h1>
</header>

<h2> Table of Contents </h2>


[TOC]


# Commandments
1. Thou shalt not modify code located outside the `app/` directory
2. Thou shalt not install third party modules without agreement that the benefits out way the negatives
    1. Thou shall only install modules from large vendors (sendgrid, salesforce, etc)
3. Thou shalt take great care to maintain the git repository integrity


# Getting Started
<div style="text-align: right;">[Top](#content)</div>

Main README [README.md](/README.md)

# Business Definitions and Terminology

**Program**
Example: Certified Insurance Counselors Institutes
**Institute**
Example: Commercial Casualty I
**Topic**
Example: Commercial General Liability Concepts and Coverage

# Purpose
## Functionality

* Setup
    1. Instantiates the product attribute set for publications
    2. Instantiates the product attribute groups for the publication attribute set
        * Verify ordering of attribute groups is acceptable
    3. Instantiates the product attributes for the publication product
    4. Assign product attributes to correct attribute groups
    5. Create catalog category for the Bookstore
* Legacy - NACS Integration
    1. Events should be synced **from** the oracle database
        * Cronjob should be added to a custom crongroup in the admin area, so end users can change sync schedule in the Magento Adminpanel under Stores > Configuration > Advanched > System
        * Events should be synced such that `Event Start date is greater than today OR Event is a university event and Event start date is great than today minus 30 days`
        * Events that are already in Magento that do not meet this criteria should be disabled, not removed, so as to keep a record in the system.
    2. Adds an observer either on the event `checkout_onepage_controller_success_action` or on `sales_order_place_after` (needs more research/test to determine proper event).
        * Observer should insert the required data into NACS
            1. Current Package and function for insertion: `$@"DECLARE result varchar2(1000); BEGIN  @0 := CIC3.CARTJSON.REQUESTENTRY(@1); END;";`
        * Error handling
            1. Should log the order, time, and error codes.
            2. Notify the current Magento administrators in the case db insertion fails.
            3. Should retry insertion periodically for the backlog of failed insertion transactions until service returns to normal.

* GateKeeper - Continuing education
    1. Must present continuing education "terms and conditions" during the checkout flow for all events
* Request Continuing education during checkout
    1. The option to request continuing education for an event during registration
    2. Events are pre certified by each state license to offer a certain number of credits for an event.
* ERP (Fusion) - Integration
    1. [Oracle Fusion Soap API Docs](http://docs.oracle.com/cloud/farel11/common/OESWA/)
* Course Schedule
    1. Event Filtering
        1. Currently, using the Mageplaza/LayeredNaviationUltimate module for product filtering capabilities
        2. Attributes that will be filtered upon should be of type dropdown with filtering on.
            * Location
            * Program
            * Institute
            * Date (Start and End)
            * Learning Options
* Register for Others

## QA Requirements



```
```


```
```

# Running the tests

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

## Contributing

Please read [CONTRIBUTING.md](./CONTRIBUTING.md) for details on our code of conduct, and the process for submitting code review.

## License

See [LICENSE.md](./LICENSE.md) file for details.

## Acknowledgments

* Hat tip to anyone who's code was used
* Inspiration
* etc

