# GiveWP - Test Data Generator

## Introduction

Generate test data using WP-CLI or from an easy-to-use admin interface.

### Getting Set Up
1. download zip file from https://github.com/impress-org/givewp-test-data
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
- Form templates - *select the form template. If both templates are selected, the donation form template will be chosen randomly for each generated form*
- Set Donation goal - *checking this option will set random donation goal for each generated donation form*
- Generate Form T&C  - *checking this option will set random Terms&Conditions for each generated donation form*

### Generate Donors screen options

- Donors count - *set the number of donors to generate*

### Generate Donations screen options

**Donation statuses**

- Status - *select the status of donations which you want to generate*
- Count - *set the number of donations to generate*
- Revenue - *set the total revenue amount of generated donations. If it's not set, the random donation amount will be used for each generated donation*

**Set donation date**

*Set the earliest donation date. The donation dates will still be randomly generated, but they won't go in the past before the selected "start date".* 

### Generate Demonstration Page screen

*Generate Page* generates a demonstration page which includes all the GiveWp shortcodes available

## CLI Commands

### Generate Donations

`wp give test-donation-form`

**Options**

`[--count=<count>]`

 Number of donations to generate
 
 default: `10`
 
 
`[--template=<template>]`

 Form template

 default: `random`
 
 options: 
 - `sequoia`
 - `legacy`
 - `random`
 
 `[--set-goal=<bool>]`
 
 Set donation form goal
 
 default: `false`

`[--set-terms=<bool>]`

 Set donation form terms and conditions

 default: `false`

`[--preview=<preview>]`

Preview generated data

default: `false`


**Example usage**

 `wp give test-donation-form --count=10 --template=legacy --set-goal=true --set-terms=true`  
 
 
 **Help**
 
 `wp help give test-donation-form`
 
 
 ### Generate Donors
 
 `wp give test-donors`
 
 **Options**
 
`[--count=<count>]`

Number of donors to generate

default: `10`

`[--preview=<preview>]`

Preview generated data

default: `false`

**Example usage**

`wp give test-donors --count=10 --preview=true`

 **Help**
 
 `wp help give test-donors`


 ### Generate Donations
 
 `wp give test-donations`
 
 **Options**
 
`[--count=<count>]`

Number of donations to generate

default: `10`

`[--status=<status>]`

Donation status

default: `publish`

options:
- `publish`
- `pending`
- `refunded`
- `cancelled`
- `random`

Get all available statuses with command:

`wp give test-donation-statuses`


`[--total-revenue=<amount>]`

Total revenue amount to be generated

default: `0`

`[--preview=<preview>]`

Preview generated data

default: `false`

`[--start-date=<date>]`

Set donation start date. Date format is `YYYY-MM-DD`

default: `false`

`[--params=<params>]`

Used for passing additional parameters

Example usage: 

`--params=donation_currency=EUR\&donation_cover_fees=true`

default: `''`

### Generate GiveWP demonstartion page

`wp give test-demonstration-page`

Generates GiveWP demonstartion page with all GiveWP shortcodes included

 **Options**
 
`[--preview=<preview>]`

Preview generated data

default: `false`

**Example usage**

`wp give test-demonstration-page --count=10 --preview=true`

 **Help**
 
 `wp help give test-demonstration-page`
