#Recipes

## Presentation
Analysis are grouped in different standard recipes, that may be run independantly. Each recipe has a focus target, 

Recipes runs all its analysis and any needed dependency.

Recipes are configured with the -T option, when running exakat in command line.

## List of recipes

Here is the list of the current recipes supported by Exakat Engine.

|Name|Description|
|---|---| 
|[Security](#security)| Check the code for common security bad practices, especially in the Web environnement. |
|Performances| Check the code for slow code. |
|Dead code| Check the unused code or unreachable code. |
|Analysis| Check for common best practices. |
|CompatibilityPHP70| List features that are incompatible with PHP 7.0. This recipe is helpful for checking compatibility. |
|CompatibilityPHP71| List features that are incompatible with PHP 7.1. This recipe is helpful for forward compatibility, and it currently under developpement. |
|CompatibilityPHP56| List features that are incompatible with PHP 5.6. This recipe is helpful for backward compatibility. |
|CompatibilityPHP55| List features that are incompatible with PHP 5.5. This recipe is helpful for backward compatibility. |
|CompatibilityPHP54| List features that are incompatible with PHP 5.4. This recipe is helpful for backward compatibility. |
|CompatibilityPHP53| List features that are incompatible with PHP 5.3. This recipe is helpful for backward compatibility. |

## Recipes details

### Security<a name="Security"></a> 

### Performances