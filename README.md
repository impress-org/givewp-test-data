# GiveWP - Test Data Generator

## Introduction

Generate test data using an easy-to-use admin interface.

### Getting Set Up
1. download zip file from https://github.com/impress-org/givewp-test-data/releases
2. Install and activate the add-on

## Admin interface

Navigate to Donations -> Tools -> Test Data

### Generating test data

There is a specific order which you have to follow when generating the test data

1. Generate Donation Forms
    - *If there are no Donation Forms available on the site, it's important to generate them first*

2. Generate Donors

3. Generate Donations

### Generate Donation Forms screen options

- Donation Forms count - *set the number of donations form to generate*
- Form templates - *select the form template. If both templates are selected, the donation form template will be chosen
  randomly for each generated form*
- Set Donation goal - *checking this option will set random donation goal for each generated donation form*
- Generate Form T&C - *checking this option will set random Terms&Conditions for each generated donation form*

### Generate Donors screen options

- Donors count - *set the number of donors to generate*

### Generate Donations screen options

**Donation statuses**

- Status - *select the status of donations which you want to generate*
- Count - *set the number of donations to generate*
- Revenue - *set the total revenue amount of generated donations. If it's not set, the random donation amount will be
  used for each generated donation*

**Set donation date**

*Set the earliest donation date. The donation dates will still be randomly generated, but they won't go in the past
before the selected "start date".*

### Generate Demonstration Page screen

*Generate Page* generates a demonstration page which includes all the GiveWp shortcodes available
