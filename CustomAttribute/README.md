# Vendor_CustomAttribute Module

## Overview

The `Vendor_CustomAttribute` module adds a custom product attribute to Magento and provides functionalities such as updating the attribute via a console command, controlling its visibility through a feature toggle, and validating user input on the product detail page. This README provides detailed instructions on how to install, configure, and test the module.

## Used environment

This project was set up using Warden. The complete documentation of how to set up your envirtonment using warden is provided on <a href="https://docs.warden.dev/environments/magento2.html">this website</a>

## Installation

### Module Setup
In your working Magento 2 instance:

1. **Clone or download the module:**
   - Extract the module to the `app/code/Vendor/CustomAttribute` directory in your Magento installation.

2. **Enable the module:**
   - php bin/magento module:enable Vendor_CustomAttribute

3. **Apply database updates:**
   - php bin/magento setup:upgrade

4. **Clear the cache:**
   - php bin/magento cache:flush

4. **Reindex and deploy static content:**
   - php bin/magento indexer:reindex
   - php bin/magento setup:static-content:deploy -f

## Module Functionalities

#### Custom Attribute Creation
The module introduces a custom product attribute named special_attribute. This attribute is a text field that can be displayed on the product detail page.

**Approach taken**:
The attribute is added during the module's installation via the InstallData.php script. This script uses the EavSetupFactory class to define the attribute's properties, such as its type (text), label, visibility (visible_on_front), and its addition to the default attribute set of Magento products.

#### Product Detail Page Modification
The module inserts a new template into a specific part of the product detail page to display the value of the special_attribute in a text input field.

**Approach taken**:
I opted to insert a new template within a targeted section of the page, specifically after the product price. This approach is cleaner and avoids disrupting the overall structure and layout of the PDP. The new template is responsible for rendering the special_attribute if it exists. I used the ViewModel pattern to pass the data from the backend to the frontend, ensuring that the special_attribute value is retrieved and available in the template. JavaScript is also included to validate the user input for special_attribute, making sure it meets the specified criteria.

#### Custom Console Commands:

Three custom console commands are implemented to manage the special_attribute:

1. Update Custom Attribute Value:
- php bin/magento customattribute:update <new_value>

**Approach taken**:
This command updates the special_attribute value for all products in the catalog. It fetches all products, sets the new value for special_attribute, and saves the products back to the database.

2. Enable Feature Toggle:
- php bin/magento customattribute:feature-toggle:enable

**Approach taken**:
This command enables the special_attribute by setting a configuration value in Magento's configuration system. It also updates the attribute's visibility on the frontend, ensuring it is displayed.


3. Disable Feature Toggle:
- php bin/magento customattribute:feature-toggle:disable

**Approach taken**:
This command disables the special_attribute by setting the configuration value to false. It also updates the attribute's visibility, ensuring it is hidden on the frontend when the feature is disabled.

#### Feature Toggle Functionality
The module includes a feature toggle that can enable or disable the visibility and functionality of special_attribute across the store.

**Approach taken**:
The feature toggle is managed via Magento's configuration system. The commands enable and disable control whether the attribute is displayed on the frontend or not.
The visibility of special_attribute on the product detail page is dynamically adjusted based on the feature toggle's state, ensuring that it only appears when the feature is enabled.

#### Magento Core Functionality Extension
The module extends Magento's core functionality by adding the special_attribute as a column in the product listing UI Component.

****Approach taken****:
By extending Magento's UI components, the module ensures that special_attribute is not only displayed on the product detail page but also included in the product listing, providing a consistent user experience.


## Testing the Module
1. Testing the Custom Attribute
Enable the customattribute navigating to **Stores > Configuration > General > Custom Attribute Settings**
or simply by running the custom command 

- **php bin/magento customattribute:feature-toggle:enable**

Its recommended running php bin/magento setup:upgrade && php bin/magento cache:flush after this command.

Then, navigate to a product detail page and observe the special_attribute field.
Ensure the field is displayed correctly and that the value meets the validation criteria.

2. Testing the Custom Console Commands
Use the console commands to update the special_attribute and toggle its visibility. Verify that the changes are reflected on the frontend.

- php bin/magento customattribute:feature-toggle:enable
- php bin/magento customattribute:feature-toggle:disable
- php bin/magento customattribute:update <value>

3. Testing the Feature Toggle
Enable and disable the feature toggle navigating to **Stores > Configuration > General > Custom Attribute Settings** or using the provided commands. Check that the special_attribute is correctly shown or hidden on the product detail page.

Conclusion
The Vendor_CustomAttribute module provides a complete solution for managing a custom product attribute within Magento. By following the instructions provided, you can install, configure, and test the module to ensure it meets your needs.

