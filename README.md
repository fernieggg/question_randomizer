# Question Randomizer

**Contributors**: Hobo Programming  
**Tags**: questions, randomizer, gravity forms, shortcode  
**Requires at least**: 5.0  
**Tested up to**: 5.8  
**Stable tag**: 1.3  
**Requires PHP**: 7.0  
**License**: GPLv2 or later  
**License URI**: [GPLv2 or later](http://www.gnu.org/licenses/gpl-2.0.html)

A plugin to display random questions using a shortcode and integrate with Gravity Forms for capturing answers.

## Description

Question Randomizer allows you to display random questions on your website using a shortcode. It integrates seamlessly with Gravity Forms to capture answers from your visitors.

## Installation

1. Upload the `question-randomizer` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Ensure Gravity Forms is installed and activated.
4. Create questions using the "Questions" custom post type.
5. Use the `[random_question form_id=your_gravity_form_id]` shortcode to display a random question and embed the Gravity Form.

## Setting Up the Gravity Form

1. Install and activate the Gravity Forms plugin.
2. Go to **Forms > New Form** to create a new form.
3. Add the following fields to your form:
   
   - **Hidden Field**:
     - Field Label: `Question`
     - Field Type: `Hidden`
     - Advanced Tab > Allow field to be populated dynamically: Check this option.
     - Parameter Name: `question`

   - **Paragraph Text Field**:
     - Field Label: `Answer`
     - Field Type: `Paragraph Text (Text Area)`
     - Field Visibility: `Visible`

4. Save the form and note the form ID.

## Shortcode Usage

To display a random question and embed the Gravity Form, use the following shortcode:

```plaintext
[random_question form_id=your_gravity_form_id]
```

## Changelog
```
1.3
* Stable Release - Finalized Plugin for Beta.
1.1
* Stable Release - Includes Gravity Forms, Formidable, and Contact Form 7.
1.0
* Initial release.
```